import type { CollectionConfig } from 'payload'
import { pageBlocks } from '../blocks'

export const Pages: CollectionConfig = {
  slug: 'pages',
  admin: {
    useAsTitle: 'title',
    defaultColumns: ['title', 'slug', '_status', 'updatedAt'],
    livePreview: {
      url: ({ data }) => {
        const base = process.env.NEXT_PUBLIC_SERVER_URL || 'http://localhost:3000'
        return `${base}/preview/pages/${data?.slug || ''}`
      },
    },
  },
  access: {
    read: () => true,
  },
  versions: {
    drafts: { autosave: false },
  },
  fields: [
    { name: 'title', type: 'text', required: true },
    {
      name: 'slug',
      type: 'text',
      required: true,
      unique: true,
      index: true,
      admin: { description: 'URL path segment, e.g. "about". Use "home" for the front page.' },
    },
    {
      name: 'layout',
      type: 'blocks',
      labels: { singular: 'Block', plural: 'Layout blocks' },
      blocks: pageBlocks,
      admin: {
        description:
          'Compose the page from typed blocks. If any blocks are present, they render INSTEAD of the legacy rawHtml/inlineStyles below.',
      },
    },
    {
      name: 'rawHtml',
      type: 'textarea',
      maxLength: 1_000_000,
      admin: {
        rows: 20,
        description:
          'Legacy: Gutenberg / Spectra HTML imported from WordPress. Used as a fallback when no layout blocks are present.',
      },
    },
    {
      name: 'inlineStyles',
      type: 'textarea',
      maxLength: 1_000_000,
      admin: {
        rows: 6,
        description:
          'Per-page CSS scraped from the live site (Spectra emits unique per-block CSS). Injected as a <style> tag at render.',
      },
    },
    {
      name: 'headExtras',
      type: 'textarea',
      maxLength: 1_000_000,
      admin: {
        rows: 6,
        description:
          'Raw <link rel="stylesheet"> and <script> tags scraped from the live <head>. ' +
          'Powers Spectra navigation, swipers, GS logo slider, embedded charts, etc. ' +
          'Injected verbatim into the page.',
      },
    },
    {
      name: 'excerpt',
      type: 'textarea',
    },
    {
      name: 'featuredImage',
      type: 'upload',
      relationTo: 'media',
    },
    {
      name: 'legacy',
      type: 'group',
      admin: { description: 'Provenance from WordPress.' },
      fields: [
        { name: 'wpId', type: 'number', index: true },
        { name: 'originalUrl', type: 'text' },
      ],
    },
  ],
}
