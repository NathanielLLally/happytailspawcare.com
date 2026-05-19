import type { CollectionConfig } from 'payload'
import path from 'path'
import { fileURLToPath } from 'url'

const filename = fileURLToPath(import.meta.url)
const dirname = path.dirname(filename)

export const Media: CollectionConfig = {
  slug: 'media',
  access: { read: () => true },
  upload: {
    staticDir: path.resolve(dirname, '../../media'),
    mimeTypes: ['image/*', 'application/pdf', 'video/*', 'audio/*'],
  },
  fields: [
    { name: 'alt', type: 'text' },
    { name: 'caption', type: 'text' },
    {
      name: 'legacy',
      type: 'group',
      admin: { description: 'Provenance from the imported WordPress site.' },
      fields: [
        { name: 'wpId', type: 'number', index: true },
        { name: 'originalUrl', type: 'text', index: true },
      ],
    },
  ],
}
