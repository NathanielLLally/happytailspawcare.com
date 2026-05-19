import Link from 'next/link'

type NavItem = { label: string; href: string; id?: string }

export function SiteFooter({
  tagline,
  copyright,
  navItems,
}: {
  tagline?: string
  copyright?: string
  navItems: NavItem[]
}) {
  const year = new Date().getFullYear()
  return (
    <footer className="site-footer">
      <div className="site-footer__inner">
        <div>
          {tagline ? <div>{tagline}</div> : null}
          <div>{copyright ?? `© ${year} Happy Tails Paw Care`}</div>
        </div>
        <nav aria-label="Footer">
          {navItems.map((item, i) => (
            <span key={item.id ?? `${item.label}-${i}`} style={{ marginLeft: i === 0 ? 0 : 12 }}>
              <Link href={item.href}>{item.label}</Link>
            </span>
          ))}
        </nav>
      </div>
    </footer>
  )
}
