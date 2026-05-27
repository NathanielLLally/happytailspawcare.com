import { notFound } from 'next/navigation'
import { getPayload } from 'payload'
import config from '@payload-config'
import { PagePreviewClient } from '@/components/PagePreviewClient'
import type { Page } from '@/payload-types'

export const dynamic = 'force-dynamic'

type Params = { slug: string }

export default async function PagePreview({ params }: { params: Promise<Params> }) {
  const { slug } = await params
  const payload = await getPayload({ config })
  const result = await payload.find({
    collection: 'pages',
    where: { slug: { equals: slug } },
    draft: true,
    limit: 1,
  })
  const page = result.docs[0] as Page | undefined
  if (!page) notFound()

  return <PagePreviewClient initialData={page} />
}
