import { notFound } from 'next/navigation'
import { getPayload } from 'payload'
import config from '@payload-config'
import { HtmlContent } from '@/components/HtmlContent'
import { LeadForm } from '@/components/LeadForm'
import { BusinessModelForm } from '@/components/BusinessModelForm'
import { stripForminator } from '@/lib/transform-learn-more'
import { PageBlocks } from '@/components/blocks'

export const dynamic = 'force-dynamic'

type Params = { slug: string }

export async function generateMetadata({ params }: { params: Promise<Params> }) {
  const { slug } = await params
  const payload = await getPayload({ config })
  const result = await payload.find({
    collection: 'pages',
    where: { slug: { equals: slug } },
    limit: 1,
  })
  const page = result.docs[0] as any
  if (!page) return {}
  return {
    title: page.title,
    description: page.excerpt ?? undefined,
  }
}

export default async function PageBySlug({ params }: { params: Promise<Params> }) {
  const { slug } = await params
  const payload = await getPayload({ config })
  const result = await payload.find({
    collection: 'pages',
    where: { slug: { equals: slug } },
    limit: 1,
  })
  const page = result.docs[0] as any
  if (!page) notFound()

  // Layout blocks take precedence over the legacy rawHtml field. When
  // both are absent, fall back to just the title.
  const hasBlocks = Array.isArray(page.layout) && page.layout.length > 0
  let renderedBody: React.ReactNode

  if (hasBlocks) {
    renderedBody = <PageBlocks blocks={page.layout} sourcePage={`/${slug}`} />
  } else if (page.rawHtml) {
    const isBusinessForm = page.rawHtml.includes('forminator-module-421')
    const isLeadForm = page.rawHtml.includes('forminator-module-420') || page.rawHtml.includes('forminator-ui')

    if (isBusinessForm || isLeadForm) {
      const { before, after } = stripForminator(page.rawHtml)
      renderedBody = (
        <>
          <HtmlContent head={page.headExtras || ''} html={before} />
          <div className="lead-form-wrap container">
            {isBusinessForm ? (
              <BusinessModelForm sourcePage={`/${slug}`} />
            ) : (
              <LeadForm sourcePage={`/${slug}`} />
            )}
          </div>
          {after ? <HtmlContent html={after} /> : null}
        </>
      )
    } else {
      renderedBody = <HtmlContent head={page.headExtras || ''} html={page.rawHtml} />
    }
  } else {
    renderedBody = (
      <div className="container">
        <h1>{page.title}</h1>
      </div>
    )
  }


  return (
    <>
      {page.inlineStyles ? (
        <style dangerouslySetInnerHTML={{ __html: page.inlineStyles }} />
      ) : null}
      <article className={`page-content page--${slug}`} data-slug={slug}>
        {renderedBody}
      </article>
    </>
  )
}
