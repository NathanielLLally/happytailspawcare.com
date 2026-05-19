import type { CollectionConfig } from 'payload'

export const Leads: CollectionConfig = {
  slug: 'leads',
  admin: {
    useAsTitle: 'email',
    defaultColumns: ['email', 'name', 'phone', 'smsOptIn', 'createdAt'],
    description: 'Form submissions from /learn-more.',
  },
  access: {
    // Anyone can submit the form.
    create: () => true,
    // Only admins (any authenticated user, for this single-user site) can read.
    read: ({ req }) => Boolean(req.user),
    update: ({ req }) => Boolean(req.user),
    delete: ({ req }) => Boolean(req.user),
  },
  fields: [
    { name: 'name', type: 'text', required: true },
    { name: 'phone', type: 'text' },
    {
      name: 'email',
      type: 'email',
      required: true,
      index: true,
    },
    { name: 'message', type: 'textarea' },
    {
      name: 'smsOptIn',
      type: 'checkbox',
      label: 'SMS opt-in',
      defaultValue: false,
    },
    {
      name: 'meta',
      type: 'group',
      admin: { description: 'Capture context — populated automatically.' },
      fields: [
        { name: 'sourcePage', type: 'text', admin: { description: 'pathname the form was submitted from' } },
        { name: 'referer', type: 'text' },
        { name: 'userAgent', type: 'text' },
        { name: 'ip', type: 'text' },
      ],
    },
  ],
  hooks: {
    beforeChange: [
      ({ req, operation, data }) => {
        if (operation !== 'create') return data
        const headers: any = req?.headers
        // Capture request metadata.
        if (!data.meta) data.meta = {}
        if (headers) {
          const get = (k: string) =>
            typeof headers.get === 'function' ? headers.get(k) : headers[k]
          if (!data.meta.referer) data.meta.referer = get('referer') || get('referrer') || ''
          if (!data.meta.userAgent) data.meta.userAgent = get('user-agent') || ''
          const fwd = get('x-forwarded-for')
          if (!data.meta.ip) data.meta.ip = (fwd || '').split(',')[0].trim() || ''
        }
        return data
      },
    ],
  },
}
