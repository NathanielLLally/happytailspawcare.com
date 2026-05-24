import Link from 'next/link'

type Cta = { label: string; href: string; variant?: 'primary' | 'secondary'; id?: string }

export type HeroData = {
  blockType: 'hero'
  eyebrow?: string
  heading: string
  subheading?: string
  backgroundImage?: { url?: string; alt?: string } | null
  alignment?: 'left' | 'center'
  ctas?: Cta[]
}

export function Hero({ data }: { data: HeroData }) {
  const bg = data.backgroundImage?.url
  return (
    <section
      className={`block-hero block-hero--${data.alignment ?? 'left'}${bg ? ' block-hero--has-bg' : ''}`}
      style={bg ? { backgroundImage: `linear-gradient(rgba(0,0,0,0.35), rgba(0,0,0,0.35)), url(${bg})` } : undefined}
    >
      <div className="container block-hero__inner">
        {data.eyebrow ? <p className="block-hero__eyebrow">{data.eyebrow}</p> : null}
        <h1 className="block-hero__heading">{data.heading}</h1>
        {data.subheading ? <p className="block-hero__sub">{data.subheading}</p> : null}
        {data.ctas?.length ? (
          <div className="block-hero__ctas">
            {data.ctas.map((c, i) => (
              <Link
                key={c.id ?? i}
                href={c.href}
                className={`block-cta block-cta--${c.variant ?? 'primary'}`}
              >
                {c.label}
              </Link>
            ))}
          </div>
        ) : null}
      </div>
    </section>
  )
}
