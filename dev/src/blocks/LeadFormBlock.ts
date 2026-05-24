import type { Block } from 'payload'

export const LeadFormBlock: Block = {
  slug: 'leadForm',
  labels: { singular: 'Lead Form', plural: 'Lead Forms' },
  fields: [
    { name: 'heading', type: 'text' },
    { name: 'intro', type: 'textarea' },
  ],
}
