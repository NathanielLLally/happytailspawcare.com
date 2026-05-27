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
      name: 'serviceType',
      type: 'select',
      options: [
        'Emergency / 24-7 Veterinary Clinics',
        'Specialty & Surgical Veterinary Practices',
        'Franchise / Multi-Location Vet Clinics',
        'Dog Training (Behavioral / Aggression / Board & Train)',
        'Pet Boarding & Daycare (Urban / High-Capacity)',
        'Mobile Veterinary Services',
        'Luxury Pet Sitting (In-Home, Overnight)',
        'High-End Grooming (Breed-specific, Show prep)',
        'Dog Walking (Urban, Recurring Packages)',
        'Pet Transportation / Pet Taxi / Airline-ready transport',
        'Pet Cremation & Memorial Services',
        'Raw / Prescription Pet Food (Local Direct To Consumer)',
        'Pet Photography / Events',
        'Exotic Pet Care (Reptiles, Birds, Small Mammals)',
        'Pet Waste Removal (Pooper Scooper)',
      ],
    },
    {
      name: 'speculationModel',
      type: 'select',
      options: [
        'Conservative',
        'Low-Key Flex',
        'Reliable Prediction',
        'Baller Bracket',
        'Well-Oiled Machine',
      ],
    },
    {
      name: 'leadsPerMonth',
      type: 'select',
      options: ['0', '10', '20', '40'],
    },
    {
      name: 'activeClients',
      type: 'number',
    },
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
