import Link from 'next/link'

type NavItem = { label: string; href: string; id?: string }

export function SiteHeader({
  siteName,
  navItems,
}: {
  siteName: string
  navItems: NavItem[]
}) {
  return (
    <header className="site-header">
      <div className="site-header__inner">
        <Link href="/" className="site-header__brand">
          {siteName}
        </Link>
        <nav className="site-nav" aria-label="Primary">
          {navItems.map((item) => (
            <Link key={item.id ?? `${item.label}-${item.href}`} href={item.href}>
              {item.label}
            </Link>
          ))}
          <Link href="/learn-more" className="nav-cta" aria-label="Get leads">
            Leads
            <svg
              className="nav-cta__arrow"
              viewBox="0 0 24 24"
              width="14"
              height="14"
              fill="none"
              stroke="currentColor"
              strokeWidth="2.5"
              strokeLinecap="round"
              strokeLinejoin="round"
              aria-hidden="true"
            >
              <path d="M5 12h14" />
              <path d="m13 5 7 7-7 7" />
            </svg>
          </Link>
        </nav>
      </div>
    </header>
  )
}
