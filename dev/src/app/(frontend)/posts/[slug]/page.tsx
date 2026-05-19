import { notFound } from 'next/navigation'
import { getPayload } from 'payload'
import config from '@payload-config'

export const dynamic = 'force-dynamic'

type Params = { slug: string }

export default async function PostBySlug({ params }: { params: Promise<Params> }) {
  const { slug } = await params
  const payload = await getPayload({ config })
  const result = await payload.find({
    collection: 'posts',
    where: { slug: { equals: slug } },
    limit: 1,
  })
  const post = result.docs[0] as any
  if (!post) notFound()

  return (
    <div className="container">
      <article className="page-content">
        <h1>{post.title}</h1>
        {post.publishedAt ? (
          <small style={{ color: 'var(--color-muted)' }}>
            {new Date(post.publishedAt).toLocaleDateString()}
          </small>
        ) : null}
        {post.rawHtml ? (
          <div
            style={{ marginTop: '1.5rem' }}
            dangerouslySetInnerHTML={{ __html: post.rawHtml }}
          />
        ) : null}
      </article>
    </div>
  )
}
