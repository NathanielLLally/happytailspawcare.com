import type { Block } from 'payload'

export const FeatureGridBlock: Block = {
  slug: 'featureGrid',
  labels: { singular: 'Feature Grid', plural: 'Feature Grids' },
  fields: [
    { name: 'heading', type: 'text' },
    { name: 'intro', type: 'textarea' },
    {
      name: 'columns',
      type: 'select',
      defaultValue: '3',
      options: [
        { label: '2 columns', value: '2' },
        { label: '3 columns', value: '3' },
        { label: '4 columns', value: '4' },
      ],
    },
    {
      name: 'items',
      type: 'array',
      minRows: 1,
      labels: { singular: 'Feature', plural: 'Features' },
      fields: [
        {
          name: 'icon',
          type: 'select',
          admin: { description: 'Inline icon — pick one from the built-in set.' },
          defaultValue: 'none',
          options: [
            { label: 'None', value: 'none' },
            { label: 'Paw', value: 'paw' },
            { label: 'Arrow', value: 'arrow' },
            { label: 'Star', value: 'star' },
            { label: 'Lead', value: 'lead' },
            { label: 'Mail', value: 'mail' },
            { label: 'Search', value: 'search' },
            { label: 'Chart', value: 'chart' },
            { label: 'Gear', value: 'gear' },
          ],
        },
        { name: 'title', type: 'text', required: true },
        { name: 'body', type: 'textarea' },
        {
          name: 'link',
          type: 'group',
          fields: [
            { name: 'label', type: 'text' },
            { name: 'href', type: 'text' },
          ],
        },
      ],
    },
  ],
}
