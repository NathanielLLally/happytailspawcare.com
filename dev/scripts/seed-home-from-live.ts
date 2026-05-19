/**
 * One-shot: fetch the live happytailspawcare.com home page, extract the
 * <div class="wp-site-blocks">…</div> body (minus its <header>), rewrite
 * uploads URLs to local Payload media, and store the result as the home
 * Page's rawHtml.
 */
import 'dotenv/config'
import { getPayload } from 'payload'
import config from '../src/payload.config.ts'

const LIVE = 'https://happytailspawcare.com'

async function main() {
  const payload = await getPayload({ config })

  const res = await fetch(`${LIVE}/`, {
    headers: { 'User-Agent': 'Mozilla/5.0 (Mirror; htpc importer)' },
  })
  if (!res.ok) throw new Error(`fetch / -> ${res.status}`)
  const src = await res.text()

  const blockMatch = src.match(/<div class="wp-site-blocks">([\s\S]*?)<footer[\s>]/)
  if (!blockMatch) throw new Error('could not locate wp-site-blocks region')
  let body = blockMatch[1]
  // Drop the leading <header>…</header> (we render our own).
  body = body.replace(/^\s*<header[^>]*>[\s\S]*?<\/header>/, '')

  // Map uploads URLs → local Payload media. Build a stem-keyed index for
  // sized variants (foo-300x300.png → foo).
  const allMedia = await payload.find({ collection: 'media', limit: 500 })
  const byStem = new Map<string, any>()
  for (const m of allMedia.docs as any[]) {
    const filename = (m.filename || '') as string
    const stem = filename.replace(/\.[^.]+$/, '')
    byStem.set(stem, m)
  }

  body = body.replace(
    /https?:\/\/happytailspawcare\.com\/wp-content\/uploads\/([^"'\s)]+)/g,
    (_full, p) => {
      const file = String(p).split('/').pop() || ''
      const baseStem = file.replace(/-\d+x\d+(?=\.[a-z]+$)/i, '').replace(/\.[^.]+$/, '')
      const doc = byStem.get(baseStem)
      if (doc) return doc.url || `/api/media/file/${doc.filename}`
      return _full
    },
  )

  // Rewrite remaining internal links to relative paths.
  body = body.replace(/https?:\/\/happytailspawcare\.com(\/[^"'\s)]*)?/g, (_m, p) => {
    const tail = p || '/'
    if (tail.startsWith('/wp-content/') || tail.startsWith('/wp-json')) return _m
    return tail
  })

  // Locate or create the home page.
  const existing = await payload.find({
    collection: 'pages',
    where: { slug: { equals: 'home' } },
    limit: 1,
  })
  if (existing.totalDocs === 0) {
    await payload.create({
      collection: 'pages',
      data: {
        title: 'Happy Tails Paw Care',
        slug: 'home',
        rawHtml: body,
        legacy: { wpId: 368, originalUrl: `${LIVE}/home/` },
        _status: 'published' as any,
      },
    })
    console.log('created home page')
  } else {
    await payload.update({
      collection: 'pages',
      id: existing.docs[0].id,
      data: { title: 'Happy Tails Paw Care', rawHtml: body, _status: 'published' as any },
    })
    console.log('updated home page')
  }
  process.exit(0)
}

main().catch((e) => {
  console.error(e)
  process.exit(1)
})
