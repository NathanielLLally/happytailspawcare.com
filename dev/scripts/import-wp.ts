/**
 * Import extracted WordPress content into Payload.
 *
 * Run after `python3 scripts/extract-wp.py`. Idempotent: re-running will
 * upsert by legacy WP IDs.
 *
 *   npm run import:wp
 */
import 'dotenv/config'
import fs from 'fs'
import path from 'path'
import { fileURLToPath } from 'url'
import { getPayload } from 'payload'
import config from '../src/payload.config.ts'

const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)

const ROOT = path.resolve(__dirname, '..')
const EXTRACT = path.join(__dirname, 'wp-extract')
const WP_UPLOADS = path.resolve(ROOT, '..', 'server_root', 'wp-content', 'uploads')

const LIVE_URL = 'https://happytailspawcare.com'
const LIVE_UPLOADS_PREFIX = `${LIVE_URL}/wp-content/uploads/`

function readJson<T>(name: string): T {
  return JSON.parse(fs.readFileSync(path.join(EXTRACT, name), 'utf-8'))
}

type MediaRow = {
  wpId: number
  title: string
  alt: string
  caption: string
  description: string
  mimeType: string
  uploadPath: string
  originalUrl: string
  date: string
}

type PageRow = {
  wpId: number
  slug: string
  title: string
  excerpt: string
  rawHtml: string
  date: string
  modified: string
  originalUrl: string
  parent: number | null
  featuredImageWpId: number | null
  status: 'published' | 'draft'
}

type PostRow = PageRow & {
  categories: number[]
  tags: number[]
}

type Term = { wpTermId: number; name: string; slug: string }
type Nav = {
  block_navigations: { wpId: number; title: string; slug: string; items: { label: string; href: string }[] }[]
}

async function main() {
  const payload = await getPayload({ config })

  // ---- 1. Admin user ------------------------------------------------------
  const users = await payload.find({ collection: 'users', limit: 1 })
  if (users.totalDocs === 0) {
    const email = process.env.ADMIN_EMAIL
    const password = process.env.ADMIN_PASSWORD
    if (!email || !password) {
      console.error(
        'No admin user exists yet. Set ADMIN_EMAIL and ADMIN_PASSWORD in .env (or env) and re-run, ' +
          'or visit /admin to create the first user through the UI.',
      )
    } else {
      await payload.create({
        collection: 'users',
        data: { email, password, name: 'Site Admin' },
      })
      console.log(`Created admin user: ${email}`)
    }
  }

  // ---- 2. Media -----------------------------------------------------------
  const mediaRows = readJson<MediaRow[]>('media.json')
  const mediaByWpId = new Map<number, any>()
  const mediaByOriginalUrl = new Map<string, any>()

  for (const row of mediaRows) {
    const srcPath = path.join(WP_UPLOADS, row.uploadPath)
    if (!fs.existsSync(srcPath)) {
      console.warn(`  ! missing on disk, skipping: ${row.uploadPath}`)
      continue
    }
    // Look up existing record by legacy id
    const existing = await payload.find({
      collection: 'media',
      where: { 'legacy.wpId': { equals: row.wpId } },
      limit: 1,
    })
    if (existing.totalDocs > 0) {
      const doc = existing.docs[0]
      mediaByWpId.set(row.wpId, doc)
      if (row.originalUrl) mediaByOriginalUrl.set(row.originalUrl, doc)
      continue
    }
    const fileBuffer = fs.readFileSync(srcPath)
    const filename = path.basename(row.uploadPath)
    const doc = await payload.create({
      collection: 'media',
      data: {
        alt: row.alt || row.title || filename,
        caption: row.caption || undefined,
        legacy: { wpId: row.wpId, originalUrl: row.originalUrl },
      },
      file: {
        data: fileBuffer,
        mimetype: row.mimeType || 'application/octet-stream',
        name: filename,
        size: fileBuffer.length,
      },
    })
    mediaByWpId.set(row.wpId, doc)
    if (row.originalUrl) mediaByOriginalUrl.set(row.originalUrl, doc)
    console.log(`  ✓ media: ${filename} -> #${doc.id}`)
  }

  // Also index by every WP URL that ends with the uploadPath, so that
  // resized variants (e.g. foo-300x300.png) referenced in HTML can fall back
  // to the original.
  const urlByBaseStem = new Map<string, any>()
  for (const row of mediaRows) {
    const doc = mediaByWpId.get(row.wpId)
    if (!doc) continue
    const stem = path.basename(row.uploadPath).replace(/\.[^.]+$/, '')
    urlByBaseStem.set(stem, doc)
  }

  function rewriteHtml(html: string): string {
    if (!html) return html
    // 1) Rewrite uploads URLs to local Payload media URL
    html = html.replace(/https?:\/\/happytailspawcare\.com\/wp-content\/uploads\/([^"'\s)]+)/g, (_full, p) => {
      // Try exact uploadPath match
      for (const [url, doc] of mediaByOriginalUrl) {
        if (url.endsWith(p)) return doc.url || `/api/media/file/${doc.filename}`
      }
      // Fall back: filename stem match (handles -300x300 variants)
      const file = String(p).split('/').pop() || ''
      const stem = file.replace(/-\d+x\d+(?=\.[a-z]+$)/i, '').replace(/\.[^.]+$/, '')
      const doc = urlByBaseStem.get(stem)
      if (doc) return doc.url || `/api/media/file/${doc.filename}`
      // Leave as-is (remote)
      return `${LIVE_UPLOADS_PREFIX}${p}`
    })
    // 2) Rewrite internal site URLs to local relative
    html = html.replace(/https?:\/\/happytailspawcare\.com(\/[^"'\s)]*)?/g, (_m, p) => {
      const tail = p || '/'
      if (tail.startsWith('/wp-content/') || tail.startsWith('/wp-json')) return _m
      return tail
    })
    return html
  }

  // ---- 3. Categories & tags ----------------------------------------------
  const categoryRows = readJson<Term[]>('categories.json')
  const tagRows = readJson<Term[]>('tags.json')
  const categoryByWpId = new Map<number, any>()
  const tagByWpId = new Map<number, any>()

  for (const t of categoryRows) {
    const existing = await payload.find({
      collection: 'categories',
      where: { 'legacy.wpTermId': { equals: t.wpTermId } },
      limit: 1,
    })
    const doc = existing.totalDocs > 0
      ? existing.docs[0]
      : await payload.create({
          collection: 'categories',
          data: { name: t.name, slug: t.slug, legacy: { wpTermId: t.wpTermId } },
        })
    categoryByWpId.set(t.wpTermId, doc)
  }
  for (const t of tagRows) {
    const existing = await payload.find({
      collection: 'tags',
      where: { 'legacy.wpTermId': { equals: t.wpTermId } },
      limit: 1,
    })
    const doc = existing.totalDocs > 0
      ? existing.docs[0]
      : await payload.create({
          collection: 'tags',
          data: { name: t.name, slug: t.slug, legacy: { wpTermId: t.wpTermId } },
        })
    tagByWpId.set(t.wpTermId, doc)
  }

  // ---- 4. Pages -----------------------------------------------------------
  const pageRows = readJson<PageRow[]>('pages.json')
  for (const p of pageRows) {
    if (!p.slug) continue
    const featuredImage = p.featuredImageWpId ? mediaByWpId.get(p.featuredImageWpId)?.id : null
    const data: any = {
      title: p.title || p.slug,
      slug: p.slug,
      rawHtml: rewriteHtml(p.rawHtml),
      excerpt: p.excerpt || undefined,
      featuredImage,
      legacy: { wpId: p.wpId, originalUrl: p.originalUrl },
      _status: p.status === 'published' ? 'published' : 'draft',
    }
    const existing = await payload.find({
      collection: 'pages',
      where: { 'legacy.wpId': { equals: p.wpId } },
      limit: 1,
    })
    if (existing.totalDocs > 0) {
      await payload.update({ collection: 'pages', id: existing.docs[0].id, data })
      console.log(`  ↻ page: ${p.slug}`)
    } else {
      await payload.create({ collection: 'pages', data })
      console.log(`  ✓ page: ${p.slug}`)
    }
  }

  // Special: ensure 'home' has a friendlier title
  const homeRes = await payload.find({ collection: 'pages', where: { slug: { equals: 'home' } }, limit: 1 })
  if (homeRes.totalDocs > 0 && (!homeRes.docs[0].title || homeRes.docs[0].title === 'Yo')) {
    await payload.update({
      collection: 'pages',
      id: homeRes.docs[0].id,
      data: { title: 'Happy Tails Paw Care' },
    })
  }

  // ---- 5. Posts -----------------------------------------------------------
  const postRows = readJson<PostRow[]>('posts.json')
  for (const p of postRows) {
    const featuredImage = p.featuredImageWpId ? mediaByWpId.get(p.featuredImageWpId)?.id : null
    const data: any = {
      title: p.title || p.slug,
      slug: p.slug || `post-${p.wpId}`,
      rawHtml: rewriteHtml(p.rawHtml),
      excerpt: p.excerpt || undefined,
      publishedAt: p.date ? new Date(p.date).toISOString() : undefined,
      featuredImage,
      categories: p.categories.map((id) => categoryByWpId.get(id)?.id).filter(Boolean),
      tags: p.tags.map((id) => tagByWpId.get(id)?.id).filter(Boolean),
      legacy: { wpId: p.wpId, originalUrl: p.originalUrl },
      _status: p.status === 'published' ? 'published' : 'draft',
    }
    const existing = await payload.find({
      collection: 'posts',
      where: { 'legacy.wpId': { equals: p.wpId } },
      limit: 1,
    })
    if (existing.totalDocs > 0) {
      await payload.update({ collection: 'posts', id: existing.docs[0].id, data })
      console.log(`  ↻ post: ${p.slug || data.slug}`)
    } else {
      await payload.create({ collection: 'posts', data })
      console.log(`  ✓ post: ${p.slug || data.slug}`)
    }
  }

  // ---- 6. Globals: site settings + header + footer ------------------------
  const options = readJson<Record<string, string>>('options.json')

  // Try to pick a logo from media
  const logo = [...mediaByWpId.values()].find((m: any) => {
    const fn: string = m.filename || ''
    return /logo/i.test(fn)
  })
  const favicon = [...mediaByWpId.values()].find((m: any) => /paw|favicon|icon/i.test(m.filename || ''))

  await payload.updateGlobal({
    slug: 'site-settings',
    data: {
      siteName: options.blogname || 'Happy Tails Paw Care',
      tagline: options.blogdescription || undefined,
      logo: logo?.id,
      favicon: favicon?.id,
    },
  })

  // Pull header nav from live site (we already know what it should be).
  // Resolves absolute happytailspawcare.com links to local paths.
  const headerNav = [
    { label: 'Services', href: '/services' },
    { label: 'About', href: '/about' },
    { label: 'Pricing', href: '/pricing' },
    { label: 'Resources', href: '/resources' },
    { label: 'Your Business Model', href: '/your-business-model' },
    { label: 'Learn More', href: '/learn-more' },
    { label: 'Blog', href: '/blog' },
  ]
  await payload.updateGlobal({ slug: 'header', data: { navItems: headerNav } })

  await payload.updateGlobal({
    slug: 'footer',
    data: {
      tagline: options.blogdescription || '',
      copyright: `© ${new Date().getFullYear()} ${options.blogname || 'Happy Tails Paw Care'}`,
      navItems: [
        { label: 'Privacy Policy', href: '/privacy-policy' },
        { label: 'About', href: '/about' },
      ],
    },
  })

  console.log('Import complete.')
  process.exit(0)
}

main().catch((err) => {
  console.error(err)
  process.exit(1)
})
