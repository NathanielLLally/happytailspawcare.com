import type { Block } from 'payload'

export const LogoTickerBlock: Block = {
  slug: 'logoTicker',
  labels: { singular: 'Logo Ticker', plural: 'Logo Tickers' },
  fields: [
    { name: 'eyebrow', type: 'text', admin: { description: 'Optional small label above the ticker.' } },
    {
      name: 'speedSeconds',
      type: 'number',
      defaultValue: 32,
      min: 8,
      max: 120,
      admin: { description: 'Seconds for one full loop. Lower = faster.' },
    },
    {
      name: 'logos',
      type: 'array',
      labels: { singular: 'Logo', plural: 'Logos' },
      minRows: 1,
      fields: [
        { name: 'image', type: 'upload', relationTo: 'media', required: true },
        { name: 'href', type: 'text' },
        { name: 'alt', type: 'text' },
      ],
    },
  ],
}
