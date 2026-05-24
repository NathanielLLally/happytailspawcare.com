import type { Block } from 'payload'

export const HtmlBlock: Block = {
  slug: 'html',
  labels: { singular: 'Raw HTML', plural: 'Raw HTML Blocks' },
  admin: {
    components: {},
  },
  fields: [
    {
      name: 'html',
      type: 'textarea',
      required: true,
      admin: {
        rows: 14,
        description:
          'Escape hatch — pasted in as-is. Inline <script> tags will be re-executed on the client.',
      },
    },
  ],
}
