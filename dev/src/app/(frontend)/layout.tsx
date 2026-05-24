import type { Metadata } from 'next'
import type { ReactNode } from 'react'
import { getPayload } from 'payload'
import config from '@payload-config'

import { SiteHeader } from '@/components/SiteHeader'
import { SiteFooter } from '@/components/SiteFooter'

import './globals.css'
import './spectra.css'

export const metadata: Metadata = {
  title: 'Happy Tails Paw Care',
  description: 'Happy Tails Paw Care',
}

export default async function FrontendLayout({ children }: { children: ReactNode }) {
  const payload = await getPayload({ config })
  const [header, footer, settings] = await Promise.all([
    payload.findGlobal({ slug: 'header' }).catch(() => ({ navItems: [] as any[] })),
    payload.findGlobal({ slug: 'footer' }).catch(() => ({ navItems: [] as any[] })),
    payload.findGlobal({ slug: 'site-settings' }).catch(() => ({ siteName: 'Happy Tails Paw Care' })),
  ])

  return (
    <html lang="en">
      <body>
        <SiteHeader
          siteName={(settings as any)?.siteName || 'Happy Tails Paw Care'}
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
