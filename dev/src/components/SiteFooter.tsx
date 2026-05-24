import Link from 'next/link'
import { SocialIcon, SocialLabels, type Platform } from './SocialIcon'

type NavItem = { label: string; href: string; id?: string }
type SocialLink = { platform: Platform; url: string; id?: string }

export function SiteFooter({
  tagline,
  copyright,
  navItems,
  socialLinks = [],
}: {
  tagline?: string
  copyright?: string
  navItems: NavItem[]
  socialLinks?: SocialLink[]
}) {
  const year = new Date().getFullYear()
  return (
    <footer className="site-footer">
      <div className="site-footer__inner">
        <div className="site-footer__brand">
          {tagline ? <div className="site-footer__tagline">{tagline}</div> : null}
          <div>{copyright ?? `© ${year} Happy Tails Paw Care`}</div>
        </div>

        {socialLinks.length > 0 ? (
          <ul className="site-footer__socials" aria-label="Social media">
            {socialLinks.map((s, i) => (
              <li key={s.id ?? `${s.platform}-${i}`}>
                <a
                  href={s.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label={SocialLabels[s.platform] ?? s.platform}
                  className={`site-footer__social site-footer__social--${s.platform}`}
                >
                  <SocialIcon platform={s.platform} />
                </a>
              </li>
            ))}
          </ul>
        ) : null}

        <nav className="site-footer__nav" aria-label="Footer">
          {navItems.map((item, i) => (
            <Link key={item.id ?? `${item.label}-${i}`} href={item.href}>
              {item.label}
            </Link>
          ))}
        </nav>
      </div>
    </footer>
  )
}
