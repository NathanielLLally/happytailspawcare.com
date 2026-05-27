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
  const serviceType = Array.isArray(body.serviceType) ? body.serviceType : []
  const speculationModel = typeof body.speculationModel === 'string' ? body.speculationModel : ''
  const leadsPerMonth = typeof body.leadsPerMonth === 'string' ? body.leadsPerMonth : ''
  const activeClients = typeof body.activeClients === 'number' ? body.activeClients : undefined
  const captchaToken = typeof body.captchaToken === 'string' ? body.captchaToken : ''

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

  // Verify reCAPTCHA if token is provided
  if (captchaToken) {
    const secretKey =
      process.env.RECAPTCHA_SECRET_KEY || '6LeCETssAAAAADYp0ixTIUn8C58fSJWUlKTbK-m4'
    try {
      const verifyRes = await fetch('https://www.google.com/recaptcha/api/siteverify', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `secret=${secretKey}&response=${captchaToken}`,
      })
      const verifyData = await verifyRes.json()
      if (!verifyData.success || (verifyData.score !== undefined && verifyData.score < 0.5)) {
        console.warn('reCAPTCHA verification failed', verifyData)
        return NextResponse.json({ message: 'reCAPTCHA verification failed.' }, { status: 400 })
      }
    } catch (err) {
      console.error('reCAPTCHA verify error', err)
      // Continue anyway if Google is down? Or fail safe? Usually fail safe.
    }
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
        serviceType,
        speculationModel,
        leadsPerMonth,
        activeClients,
        meta: { sourcePage },
      },
      // Skip access checks since this route is intentionally public.
      overrideAccess: true,
      req: { headers: req.headers } as any,
    })

    // If this is a business model submission, sync to Google Sheet
    if (serviceType.length > 0 || speculationModel) {
      const gScriptUrl = new URL(
        'https://script.google.com/macros/s/AKfycbwJXeC4vPpjUAZGqNQ-_qq8cKTB5g5tSSIyvfDzOchjxNv9V_Z4auBwC-adljfYQ2m6/exec',
      )
      gScriptUrl.searchParams.set('action', 'update')
      gScriptUrl.searchParams.set('name', name)
      gScriptUrl.searchParams.set('email', email)
      gScriptUrl.searchParams.set('phone', phone)
      gScriptUrl.searchParams.set('service_type', serviceType.join(', '))
      gScriptUrl.searchParams.set('speculation_model', speculationModel)
      gScriptUrl.searchParams.set('leads', leadsPerMonth)
      gScriptUrl.searchParams.set('active_clients', String(activeClients ?? 0))
      gScriptUrl.searchParams.set('source', sourcePage)

      // Fire and forget (don't block the response), but log errors.
      // We don't await so the user gets a fast response.
      fetch(gScriptUrl.toString(), { method: 'POST' }).catch((e) => {
        console.error('Google Sheet sync failed', e)
      })
    }

    return NextResponse.json({ id: doc.id }, { status: 201 })
  } catch (err: any) {
    console.error('lead submit failed', err)
    return NextResponse.json(
      { message: err?.message || 'Submission failed.' },
      { status: 500 },
    )
  }
}
