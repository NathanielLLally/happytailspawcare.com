/**
 * Public lead-form endpoint. Wraps Payload's local API so the LeadForm
 * doesn't have to deal with Payload's auth headers or the `data` envelope.
 * Validation happens twice: shallow here (typos, missing fields) and
 * deeper inside Payload via the collection's field validators.
 */
import { NextRequest, NextResponse } from 'next/server'
import { getPayload } from 'payload'
import config from '@payload-config'

export const runtime = 'nodejs'

const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

export async function POST(req: NextRequest) {
  let body: any
  try {
    body = await req.json()
  } catch {
    return NextResponse.json({ message: 'Invalid JSON body.' }, { status: 400 })
  }

  const name = typeof body.name === 'string' ? body.name.trim() : ''
  const email = typeof body.email === 'string' ? body.email.trim() : ''
  const phone = typeof body.phone === 'string' ? body.phone.trim() : ''
  const message = typeof body.message === 'string' ? body.message.trim() : ''
  const smsOptIn = Boolean(body.smsOptIn)
  const sourcePage =
    body?.meta?.sourcePage && typeof body.meta.sourcePage === 'string'
      ? body.meta.sourcePage
      : ''

  if (!name) {
    return NextResponse.json({ message: 'Name is required.' }, { status: 400 })
  }
  if (!email || !EMAIL_RE.test(email)) {
    return NextResponse.json({ message: 'A valid email is required.' }, { status: 400 })
  }
  if (name.length > 200 || email.length > 200 || phone.length > 60 || message.length > 4000) {
    return NextResponse.json({ message: 'Field too long.' }, { status: 400 })
  }

  try {
    const payload = await getPayload({ config })
    const doc = await payload.create({
      collection: 'leads',
      data: {
        name,
        email,
        phone: phone || undefined,
        message: message || undefined,
        smsOptIn,
        meta: { sourcePage },
      },
      // Skip access checks since this route is intentionally public.
      overrideAccess: true,
      req: { headers: req.headers } as any,
    })
    return NextResponse.json({ id: doc.id }, { status: 201 })
  } catch (err: any) {
    console.error('lead submit failed', err)
    return NextResponse.json(
      { message: err?.message || 'Submission failed.' },
      { status: 500 },
    )
  }
}
