import Link from 'next/link'

type IconName =
  | 'none' | 'paw' | 'arrow' | 'star' | 'lead' | 'mail' | 'search' | 'chart' | 'gear'

const ICONS: Record<Exclude<IconName, 'none'>, JSX.Element> = {
  paw: (
    <path d="M6 13a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm12 0a3 3 0 1 1 0-6 3 3 0 0 1 0 6ZM9.5 8.5a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7Zm5 0a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7ZM12 13c4 0 7 3 7 6.5a2.5 2.5 0 0 1-3.06 2.43L13 21.1l-2.94.83A2.5 2.5 0 0 1 7 19.5C7 16 10 13 12 13Z" />
  ),
  arrow: (
    <>
      <circle cx="12" cy="12" r="9" />
      <path d="M8 12h8M13 8l4 4-4 4" />
    </>
  ),
  star: (
    <path d="M12 2 14.9 8.6 22 9.3l-5.3 4.7L18 22 12 18.4 6 22l1.3-8L2 9.3l7.1-.7L12 2Z" />
  ),
  lead: (
    <path d="M3 6h13l4 4-4 4H3a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1Z" />
  ),
  mail: (
    <>
      <rect x="3" y="5" width="18" height="14" rx="2" />
      <path d="m4 7 8 6 8-6" />
    </>
  ),
  search: (
    <>
      <circle cx="11" cy="11" r="7" />
      <path d="m20 20-3.5-3.5" />
    </>
  ),
  chart: (
    <>
      <path d="M4 20V10" />
      <path d="M10 20V4" />
      <path d="M16 20v-7" />
      <path d="M22 20H2" />
    </>
  ),
  gear: (
    <>
      <circle cx="12" cy="12" r="3" />
      <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06A2 2 0 1 1 4.29 16.96l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06A2 2 0 1 1 7.04 4.29l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09c0 .67.4 1.27 1 1.51a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.24.6.84 1 1.51 1H21a2 2 0 1 1 0 4h-.09c-.67 0-1.27.4-1.51 1Z" />
    </>
  ),
}

function Icon({ name }: { name: IconName }) {
  if (name === 'none') return null
  const path = ICONS[name]
  if (!path) return null
  return (
    <svg
      className="block-featuregrid__icon"
      viewBox="0 0 24 24"
      width="32"
      height="32"
      fill="none"
      stroke="currentColor"
      strokeWidth="2"
      strokeLinecap="round"
      strokeLinejoin="round"
      aria-hidden="true"
    >
      {path}
    </svg>
  )
}

export type FeatureGridData = {
  blockType: 'featureGrid'
  heading?: string
  intro?: string
  columns?: '2' | '3' | '4'
  items?: {
    icon?: IconName
    title: string
    body?: string
    link?: { label?: string; href?: string }
    id?: string
  }[]
}

export function FeatureGrid({ data }: { data: FeatureGridData }) {
  const cols = data.columns ?? '3'
  return (
    <section className="block-featuregrid">
      <div className="container">
        {data.heading ? <h2 className="block-featuregrid__heading">{data.heading}</h2> : null}
        {data.intro ? <p className="block-featuregrid__intro">{data.intro}</p> : null}
        <ul className={`block-featuregrid__grid cols-${cols}`}>
          {data.items?.map((item, i) => {
            const inner = (
              <>
                <Icon name={item.icon ?? 'none'} />
                <h3>{item.title}</h3>
                {item.body ? <p>{item.body}</p> : null}
                {item.link?.label && item.link?.href ? (
                  <span className="block-featuregrid__cta">
                    {item.link.label} <span aria-hidden>→</span>
                  </span>
                ) : null}
              </>
            )
            return (
              <li key={item.id ?? i} className="block-featuregrid__card">
                {item.link?.href ? (
                  <Link href={item.link.href} className="block-featuregrid__card-link">
                    {inner}
                  </Link>
                ) : (
                  inner
                )}
              </li>
            )
          })}
        </ul>
      </div>
    </section>
  )
}
