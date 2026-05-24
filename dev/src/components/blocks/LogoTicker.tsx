type LogoTickerData = {
  blockType: 'logoTicker'
  eyebrow?: string
  speedSeconds?: number
  logos?: { image?: { url?: string; alt?: string }; href?: string; alt?: string; id?: string }[]
}

export function LogoTicker({ data }: { data: LogoTickerData }) {
  const speed = data.speedSeconds ?? 32
  const logos = data.logos ?? []
  if (logos.length === 0) return null
  return (
    <section className="block-logoticker">
      <div className="container">
        {data.eyebrow ? <p className="block-logoticker__eyebrow">{data.eyebrow}</p> : null}
        <div className="htpc-ticker" data-htpc-ticker="block">
          <div
            className="swiper-wrapper"
            style={{ animationDuration: `${speed}s` }}
          >
            {[...logos, ...logos].map((logo, i) => {
              const url = logo.image?.url
              const alt = logo.alt ?? logo.image?.alt ?? ''
              if (!url) return null
              const img = (
                /* eslint-disable-next-line @next/next/no-img-element */
                <img src={url} alt={alt} loading="lazy" />
              )
              return (
                <div key={i} className="swiper-slide">
                  {logo.href ? (
                    <a href={logo.href} target="_blank" rel="noopener noreferrer">
                      {img}
                    </a>
                  ) : (
                    img
                  )}
                </div>
              )
            })}
          </div>
        </div>
      </div>
    </section>
  )
}
