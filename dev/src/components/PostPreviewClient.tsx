'use client'

import { useLivePreview } from '@payloadcms/live-preview-react'
import type { Post } from '@/payload-types'

const SERVER_URL = process.env.NEXT_PUBLIC_SERVER_URL || 'http://localhost:3000'

export function PostPreviewClient({ initialData }: { initialData: Post }) {
  const { data } = useLivePreview<Post>({
    initialData,
    serverURL: SERVER_URL,
    depth: 1,
  })

  return (
    <div className="container">
      <article className="page-content">
        <h1>{data.title}</h1>
        {data.publishedAt ? (
          <small style={{ color: 'var(--color-muted)' }}>
            {new Date(data.publishedAt).toLocaleDateString()}
          </small>
        ) : null}
        {data.rawHtml ? (
          <div
            style={{ marginTop: '1.5rem' }}
            dangerouslySetInnerHTML={{ __html: data.rawHtml }}
          />
        ) : null}
      </article>
    </div>
  )
}
