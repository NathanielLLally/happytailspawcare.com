import type { GlobalConfig } from 'payload'

export const SiteSettings: GlobalConfig = {
  slug: 'site-settings',
  access: { read: () => true },
  fields: [
    { name: 'siteName', type: 'text', defaultValue: 'Happy Tails Paw Care' },
    { name: 'tagline', type: 'text' },
    { name: 'logo', type: 'upload', relationTo: 'media' },
    { name: 'favicon', type: 'upload', relationTo: 'media' },
  ],
}
