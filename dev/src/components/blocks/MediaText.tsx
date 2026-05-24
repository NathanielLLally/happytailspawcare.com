import Link from 'next/link'
import { RichText as PayloadRichText } from '@payloadcms/richtext-lexical/react'

export type MediaTextData = {
  blockType: 'mediaText'
  heading?: string
  body?: any
  image: { url?: string; alt?: string; width?: number; height?: number } | null
  imagePosition?: 'left' | 'right'
  cta?: { label?: string; href?: string }
}

export function MediaText({ data }: { data: MediaTextData }) {
  const reverse = data.imagePosition === 'right'
  return (
    <section className={`block-mediatext block-mediatext--${reverse ? 'image-right' : 'image-left'}`}>
      <div className="container block-mediatext__inner">
        {data.image?.url ? (
          <div className="block-mediatext__media">
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img src={data.image.url} alt={data.image.alt ?? ''} loading="lazy" />
          </div>
        ) : null}
        <div className="block-mediatext__text">
          {data.heading ? <h2>{data.heading}</h2> : null}
          {data.body ? <PayloadRichText data={data.body} /> : null}
          {data.cta?.label && data.cta?.href ? (
            <Link href={data.cta.href} className="block-cta block-cta--primary">
              {data.cta.label}
            </Link>
          ) : null}
        </div>
      </div>
    </section>
  )
}
