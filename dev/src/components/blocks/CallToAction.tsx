import Link from 'next/link'

export type CallToActionData = {
  blockType: 'cta'
  heading: string
  body?: string
  primary?: { label?: string; href?: string }
  secondary?: { label?: string; href?: string }
  tone?: 'light' | 'accent' | 'dark'
}

export function CallToAction({ data }: { data: CallToActionData }) {
  return (
    <section className={`block-cta-section block-cta-section--${data.tone ?? 'light'}`}>
      <div className="container block-cta-section__inner">
        <div>
          <h2>{data.heading}</h2>
          {data.body ? <p>{data.body}</p> : null}
        </div>
        <div className="block-cta-section__buttons">
          {data.primary?.label && data.primary?.href ? (
            <Link href={data.primary.href} className="block-cta block-cta--primary">
              {data.primary.label}
            </Link>
          ) : null}
          {data.secondary?.label && data.secondary?.href ? (
            <Link href={data.secondary.href} className="block-cta block-cta--secondary">
              {data.secondary.label}
            </Link>
          ) : null}
        </div>
      </div>
    </section>
  )
}
