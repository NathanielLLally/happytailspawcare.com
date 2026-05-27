'use client'

import { useLivePreview } from '@payloadcms/live-preview-react'
import type { Page } from '@/payload-types'
import { PageBlocks } from '@/components/blocks'
import { HtmlContent } from '@/components/HtmlContent'
import { LeadForm } from '@/components/LeadForm'
import { stripForminator } from '@/lib/transform-learn-more'

const SERVER_URL = process.env.NEXT_PUBLIC_SERVER_URL || 'http://localhost:3000'

export function PagePreviewClient({ initialData }: { initialData: Page }) {
  const { data } = useLivePreview<Page>({
    initialData,
    serverURL: SERVER_URL,
    depth: 1,
  })

  const hasBlocks = Array.isArray(data.layout) && data.layout.length > 0

  if (hasBlocks) {
    return (
      <article className={`page-content page--${data.slug}`} data-slug={data.slug}>
        {data.inlineStyles ? (
          <style dangerouslySetInnerHTML={{ __html: data.inlineStyles }} />
        ) : null}
        <PageBlocks blocks={data.layout as any[]} sourcePage={`/${data.slug}`} />
      </article>
    )
  }

  if (data.rawHtml && data.slug === 'learn-more') {
    const { before, after } = stripForminator(data.rawHtml)
    return (
      <>
        {data.inlineStyles ? (
          <style dangerouslySetInnerHTML={{ __html: data.inlineStyles }} />
        ) : null}
        <article className="page-content page--learn-more" data-slug={data.slug}>
          <HtmlContent head={data.headExtras || ''} html={before} />
          <div className="lead-form-wrap container">
            <LeadForm sourcePage="/learn-more" />
          </div>
          {after ? <HtmlContent html={after} /> : null}
        </article>
      </>
    )
  }

  if (data.rawHtml) {
    return (
      <>
        {data.inlineStyles ? (
          <style dangerouslySetInnerHTML={{ __html: data.inlineStyles }} />
        ) : null}
        <article className={`page-content page--${data.slug}`} data-slug={data.slug}>
          <HtmlContent head={data.headExtras || ''} html={data.rawHtml} />
        </article>
      </>
    )
  }

  return (
    <div className="container">
      <h1>{data.title}</h1>
    </div>
  )
}
