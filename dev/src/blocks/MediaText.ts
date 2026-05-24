import type { Block } from 'payload'

export const MediaTextBlock: Block = {
  slug: 'mediaText',
  labels: { singular: 'Media + Text', plural: 'Media + Text Sections' },
  fields: [
    { name: 'heading', type: 'text' },
    { name: 'body', type: 'richText' },
    {
      name: 'image',
      type: 'upload',
      relationTo: 'media',
      required: true,
    },
    {
      name: 'imagePosition',
      type: 'select',
      defaultValue: 'left',
      options: [
        { label: 'Image on left', value: 'left' },
        { label: 'Image on right', value: 'right' },
      ],
    },
    {
      name: 'cta',
      type: 'group',
      fields: [
        { name: 'label', type: 'text' },
        { name: 'href', type: 'text' },
      ],
    },
  ],
}
