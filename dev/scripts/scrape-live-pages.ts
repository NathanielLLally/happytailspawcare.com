/**
 * Re-import each Page from the live happytailspawcare.com render. The SQL
 * dump's post_content lacks Spectra's per-page generated CSS (each block has
 * a unique id like `uagb-block-b51d9d99`). On the live site those styles are
 * emitted as a `<style id="uagb-style-frontend-0">` block in <head>. We grab
 * both the body and all `<style>` tags so the page actually renders.
 *
 * Run after `npm run import:wp`.
 */
import 'dotenv/config'
import { getPayload } from 'payload'
import config from '../src/payload.config.ts'

const LIVE = 'https://happytailspawcare.com'

const STYLE_IDS_TO_KEEP = [
  // Per-page UAGB / Spectra block CSS.
  /^uagb-style-frontend(-\d+)?$/,
  // Global styles inlined by WP that reference the page's wp-presets.
  /^global-styles-inline-css$/,
  // Spectra theme & gutenberg layout inline tweaks.
  /^spectra-one-inline-css$/,
  /^core-block-supports-inline-css$/,
  /^essential-blocks-global-styles$/,
  // GS logo slider per-slider style.
  /^wp-gs-logo-style-/,
]

function shouldKeepStyle(id: string | undefined): boolean {
  if (!id) return false
  return STYLE_IDS_TO_KEEP.some((re) => re.test(id))
}

function extractStyles(html: string): string {
  const out: string[] = []
  const re = /<style\s+([^>]*?)>([\s\S]*?)<\/style>/g
  let m: RegExpExecArray | null
  while ((m = re.exec(html))) {
    const attrs = m[1] || ''
    const idMatch = /id=['"]([^'"]+)['"]/.exec(attrs)
    const id = idMatch?.[1]
    if (!shouldKeepStyle(id)) continue
    out.push(`/* ${id} */\n${m[2].trim()}`)
  }
  return out.join('\n\n')
}

/**
 * Pull <link rel="stylesheet"> and <script> tags from <head>. These power
 * Spectra navigation interactivity, the GS logo slider, swiper, the WP
 * interactivity-API module loader, etc. Skip anything tied strictly to the
 * editor / admin / analytics / WP login.
 */
function extractHeadExtras(html: string): string {
  const headMatch = html.match(/<head[^>]*>([\s\S]*?)<\/head>/i)
  if (!headMatch) return ''
  const head = headMatch[1]
  const out: string[] = []

  const SKIP = [
    /yoast/i,
    /google-tag-manager/i,
    /gtm\.js/i,
    /\/wp-admin\//i,
    /\/wp-login\//i,
    /\/wp-includes\/js\/wp-emoji-release/i,
    /admin-ajax\.php/i,
  ]

  const linkRe = /<link\s+([^>]*?)\/?>/g
  let lm: RegExpExecArray | null
  while ((lm = linkRe.exec(head))) {
    const tag = lm[0]
    if (!/rel=['"](stylesheet|preconnect|dns-prefetch|preload)['"]/i.test(tag)) continue
    if (SKIP.some((r) => r.test(tag))) continue
    out.push(tag)
  }

  const scriptRe = /<script\b([^>]*)>([\s\S]*?)<\/script>/g
  let sm: RegExpExecArray | null
  while ((sm = scriptRe.exec(head))) {
    const attrs = sm[1] || ''
    const body = sm[2] || ''
    if (SKIP.some((r) => r.test(attrs))) continue
    // Skip Yoast's huge JSON-LD blobs — they're SEO, not behavior.
    if (/type=['"]application\/ld\+json['"]/i.test(attrs)) continue
    // Skip embed.js etc that adds nothing here
    if (/wp-includes\/js\/wp-embed\.min/i.test(attrs)) continue
    out.push(`<script${attrs}>${body}</script>`)
  }
  return out.join('\n')
}

function extractBody(html: string): string {
  const m = html.match(/<div class="wp-site-blocks">([\s\S]*?)<footer[\s>]/)
  if (!m) return ''
  let body = m[1]
  body = body.replace(/^\s*<header[^>]*>[\s\S]*?<\/header>/, '')
  return body.trim()
}

async function main() {
  const payload = await getPayload({ config })

  // Build media stem index (for URL rewriting).
  const allMedia = await payload.find({ collection: 'media', limit: 500 })
  const byStem = new Map<string, any>()
  for (const m of allMedia.docs as any[]) {
    const stem = String(m.filename || '').replace(/\.[^.]+$/, '')
    byStem.set(stem, m)
  }

  function rewriteUrls(input: string): string {
    if (!input) return input
    input = input.replace(/https?:\/\/happytailspawcare\.com\/wp-content\/uploads\/([^"'\s)]+)/g, (full, p) => {
      const file = String(p).split('/').pop() || ''
      const baseStem = file.replace(/-\d+x\d+(?=\.[a-z]+$)/i, '').replace(/\.[^.]+$/, '')
      const doc = byStem.get(baseStem)
      if (doc) return doc.url || `/api/media/file/${doc.filename}`
      return full
    })
    input = input.replace(/https?:\/\/happytailspawcare\.com(\/[^"'\s)]*)?/g, (full, p) => {
      const tail = p || '/'
      if (tail.startsWith('/wp-content/') || tail.startsWith('/wp-json')) return full
      return tail
    })
    return input
  }

  const pages = await payload.find({ collection: 'pages', limit: 100 })

  for (const page of pages.docs as any[]) {
    const slug = page.slug
    if (!slug) continue
    const url = slug === 'home' ? `${LIVE}/` : `${LIVE}/${slug}/`
    process.stdout.write(`fetching ${url} ... `)
    let res: Response
    try {
      res = await fetch(url, { headers: { 'User-Agent': 'Mozilla/5.0 (htpc mirror)' } })
    } catch (e: any) {
      console.log('NETWORK FAIL', e?.message)
      continue
    }
    if (!res.ok) {
      console.log(`HTTP ${res.status}`)
      continue
    }
    const html = await res.text()
    const body = extractBody(html)
    if (!body) {
      console.log('no wp-site-blocks region')
      continue
    }
    const styles = extractStyles(html)
    const head = extractHeadExtras(html)
    const rewrittenBody = rewriteUrls(body)
    const rewrittenStyles = rewriteUrls(styles)
    // Leave uploads URLs in <head> on the live origin so the browser can
    // fetch them. Rewrite internal site links only.
    const rewrittenHead = head.replace(
      /https?:\/\/happytailspawcare\.com(\/[^"'\s)>]*)?/g,
      (full, p) => {
        const tail = p || '/'
        if (tail.startsWith('/wp-content/') || tail.startsWith('/wp-includes/') || tail.startsWith('/wp-json'))
          return full
        return tail
      },
    )
    try {
      await payload.update({
        collection: 'pages',
        id: page.id,
        data: {
          rawHtml: rewrittenBody,
          inlineStyles: rewrittenStyles,
          headExtras: rewrittenHead,
        },
      })
      console.log(
        `OK  body=${rewrittenBody.length}b  styles=${rewrittenStyles.length}b  head=${rewrittenHead.length}b`,
      )
    } catch (e: any) {
      console.log(`FAILED slug=${slug}`)
      const errs = e?.data?.errors || []
      for (const er of errs) {
        console.log('  field error:', JSON.stringify(er, null, 2).slice(0, 600))
      }
      if (!errs.length) console.log('  ', e?.message)
    }
  }

  process.exit(0)
}

main().catch((e) => {
  console.error(e)
  process.exit(1)
})
