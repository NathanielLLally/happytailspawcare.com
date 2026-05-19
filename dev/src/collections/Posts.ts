import type { CollectionConfig } from 'payload'

export const Posts: CollectionConfig = {
  slug: 'posts',
  admin: {
    useAsTitle: 'title',
    defaultColumns: ['title', 'slug', 'publishedAt', '_status'],
  },
  access: { read: () => true },
  versions: { drafts: { autosave: false } },
  fields: [
    { name: 'title', type: 'text', required: true },
    { name: 'slug', type: 'text', required: true, unique: true, index: true },
    { name: 'publishedAt', type: 'date' },
    { name: 'excerpt', type: 'textarea' },
    {
      name: 'rawHtml',
      type: 'textarea',
      admin: { rows: 20, description: 'Imported HTML body.' },
    },
    { name: 'featuredImage', type: 'upload', relationTo: 'media' },
    {
      name: 'categories',
      type: 'relationship',
      relationTo: 'categories',
      hasMany: true,
    },
    {
      name: 'tags',
      type: 'relationship',
      relationTo: 'tags',
      hasMany: true,
    },
    {
      name: 'legacy',
      type: 'group',
      fields: [
        { name: 'wpId', type: 'number', index: true },
        { name: 'originalUrl', type: 'text' },
      ],
    },
  ],
}
