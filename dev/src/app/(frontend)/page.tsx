import Link from 'next/link'
import { getPayload } from 'payload'
import config from '@payload-config'
import { HtmlContent } from '@/components/HtmlContent'
import { transformHomeHtml } from '@/lib/transform-home'
import { PageBlocks } from '@/components/blocks'

export const dynamic = 'force-dynamic'

export default async function HomePage() {
  const payload = await getPayload({ config })

  // Prefer a Page with slug 'home' if it has content; otherwise render
  // the blog index (WP's behavior was: page_for_posts=368 'home', empty content).
  const homeResult = await payload.find({
    collection: 'pages',
    where: { slug: { equals: 'home' } },
    limit: 1,
  })
  const home = homeResult.docs[0] as any

  const postsResult = await payload.find({
    collection: 'posts',
    where: { _status: { equals: 'published' } },
    sort: '-publishedAt',
    limit: 20,
  })

  const hasBlocks = Array.isArray(home?.layout) && home.layout.length > 0
  const hasHomeContent = !hasBlocks && home?.rawHtml && home.rawHtml.trim().length > 0

  return (
    <div className="container">
      {hasBlocks ? (
        <article className="page-content page--home">
          <PageBlocks blocks={home.layout} sourcePage="/" />
        </article>
      ) : hasHomeContent ? (
        <>
          {home.inlineStyles ? (
            <style dangerouslySetInnerHTML={{ __html: home.inlineStyles }} />
          ) : null}
          <article className="page-content page--home">
            <HtmlContent
              head={home.headExtras || ''}
              html={transformHomeHtml(home.rawHtml)}
            />
          </article>
        </>
      ) : (
        <>
          <h1 style={{ marginTop: '1rem' }}>{home?.title ?? 'Happy Tails Paw Care'}</h1>
          {postsResult.docs.length === 0 ? (
            <p style={{ color: 'var(--color-muted)' }}>No posts yet.</p>
          ) : (
            <ul className="post-list">
              {postsResult.docs.map((post: any) => (
                <li key={post.id} className="post-card">
                  <h2>
                    <Link href={`/posts/${post.slug}`}>{post.title}</Link>
                  </h2>
                  {post.excerpt ? <p>{post.excerpt}</p> : null}
                  {post.publishedAt ? (
                    <small style={{ color: 'var(--color-muted)' }}>
                      {new Date(post.publishedAt).toLocaleDateString()}
                    </small>
                  ) : null}
                </li>
              ))}
            </ul>
          )}
        </>
      )}
    </div>
  )
}
