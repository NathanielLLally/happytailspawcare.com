import type { Metadata } from 'next'
import type { ReactNode } from 'react'
import { getPayload } from 'payload'
import config from '@payload-config'

import { SiteHeader } from '@/components/SiteHeader'
import { SiteFooter } from '@/components/SiteFooter'

import './globals.css'
import './blocks.css'
import './spectra.css'

function mediaUrl(m: any): string | undefined {
  if (!m) return undefined
  if (typeof m === 'string') return undefined
  return m.url || (m.filename ? `/api/media/file/${m.filename}` : undefined)
}

export async function generateMetadata(): Promise<Metadata> {
  try {
    const payload = await getPayload({ config })
    const settings: any = await payload.findGlobal({ slug: 'site-settings', depth: 1 })
    const favicon = mediaUrl(settings?.favicon)
    return {
      title: settings?.siteName || 'Happy Tails Paw Care',
      description: settings?.tagline || 'Happy Tails Paw Care',
      icons: favicon
        ? {
            icon: [{ url: favicon, type: 'image/png' }],
            shortcut: favicon,
            apple: favicon,
          }
        : undefined,
    }
  } catch {
    return { title: 'Happy Tails Paw Care' }
  }
}

export default async function FrontendLayout({ children }: { children: ReactNode }) {
  const payload = await getPayload({ config })
  const [header, footer, settings] = await Promise.all([
    payload.findGlobal({ slug: 'header' }).catch(() => ({ navItems: [] as any[] })),
    payload.findGlobal({ slug: 'footer' }).catch(() => ({ navItems: [] as any[] })),
    payload
      .findGlobal({ slug: 'site-settings', depth: 1 })
      .catch(() => ({ siteName: 'Happy Tails Paw Care' })),
  ])

  const logoUrl = mediaUrl((settings as any)?.logo)
  const siteName = (settings as any)?.siteName || 'Happy Tails Paw Care'

  return (
    <html lang="en">
      <body>
        <SiteHeader
          siteName={siteName}
          logoUrl={logoUrl}
          navItems={(header as any)?.navItems || []}
        />
        <main className="site-main">{children}</main>
        <SiteFooter
          tagline={(footer as any)?.tagline}
          copyright={(footer as any)?.copyright}
          navItems={(footer as any)?.navItems || []}
          socialLinks={(footer as any)?.socialLinks || []}
        />
      </body>
    </html>
  )
}
