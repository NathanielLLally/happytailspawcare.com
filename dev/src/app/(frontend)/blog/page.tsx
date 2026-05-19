import Link from 'next/link'
import { getPayload } from 'payload'
import config from '@payload-config'

export const dynamic = 'force-dynamic'

export const metadata = { title: 'Blog — Happy Tails Paw Care' }

export default async function BlogIndex() {
  const payload = await getPayload({ config })
  const result = await payload.find({
    collection: 'posts',
    where: { _status: { equals: 'published' } },
    sort: '-publishedAt',
    limit: 50,
  })

  return (
    <div className="container">
      <h1 style={{ marginTop: '1rem' }}>Blog</h1>
      {result.docs.length === 0 ? (
        <p style={{ color: 'var(--color-muted)' }}>No posts yet.</p>
      ) : (
        <ul className="post-list">
          {result.docs.map((post: any) => (
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
    </div>
  )
}
