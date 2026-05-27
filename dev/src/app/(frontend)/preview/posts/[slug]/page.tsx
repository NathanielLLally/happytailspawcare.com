import { notFound } from 'next/navigation'
import { getPayload } from 'payload'
import config from '@payload-config'
import { PostPreviewClient } from '@/components/PostPreviewClient'
import type { Post } from '@/payload-types'

export const dynamic = 'force-dynamic'

type Params = { slug: string }

export default async function PostPreview({ params }: { params: Promise<Params> }) {
  const { slug } = await params
  const payload = await getPayload({ config })
  const result = await payload.find({
    collection: 'posts',
    where: { slug: { equals: slug } },
    draft: true,
    limit: 1,
  })
  const post = result.docs[0] as Post | undefined
  if (!post) notFound()

  return <PostPreviewClient initialData={post} />
}
