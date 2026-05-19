'use client'

import { useState, type FormEvent } from 'react'

type State =
  | { kind: 'idle' }
  | { kind: 'submitting' }
  | { kind: 'success' }
  | { kind: 'error'; message: string }

export function LeadForm({ sourcePage = '/learn-more' }: { sourcePage?: string }) {
  const [state, setState] = useState<State>({ kind: 'idle' })

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault()
    const form = event.currentTarget
    const data = new FormData(form)

    // Honeypot — bots usually fill every field. Real users leave this empty.
    if ((data.get('company_website') as string)?.trim()) {
      setState({ kind: 'success' }) // fake success, silently drop
      return
    }

    const payload = {
      name: String(data.get('name') || '').trim(),
      phone: String(data.get('phone') || '').trim(),
      email: String(data.get('email') || '').trim(),
      message: String(data.get('message') || '').trim(),
      smsOptIn: Boolean(data.get('smsOptIn')),
      meta: { sourcePage },
    }

    if (!payload.name || !payload.email) {
      setState({ kind: 'error', message: 'Name and email are required.' })
      return
    }

    setState({ kind: 'submitting' })
    try {
      const res = await fetch('/api/leads', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      })
      if (!res.ok) {
        const body = await res.json().catch(() => ({}))
        const detail =
          body?.errors?.[0]?.message ||
          body?.message ||
          `Submission failed (HTTP ${res.status})`
        throw new Error(detail)
      }
      setState({ kind: 'success' })
      form.reset()
    } catch (e: any) {
      setState({ kind: 'error', message: e?.message || 'Something went wrong.' })
    }
  }

  if (state.kind === 'success') {
    return (
      <div className="lead-form lead-form--success" role="status">
        <h3>Thanks — message received.</h3>
        <p>We&rsquo;ll be in touch shortly at the email you provided.</p>
      </div>
    )
  }

  const disabled = state.kind === 'submitting'

  return (
    <form className="lead-form" onSubmit={onSubmit} noValidate>
      <div className="lead-form__field">
        <label htmlFor="lead-name">
          <span aria-hidden>👤</span> First Name <span className="req">*</span>
        </label>
        <input id="lead-name" name="name" type="text" autoComplete="given-name" required disabled={disabled} />
      </div>

      <div className="lead-form__field">
        <label htmlFor="lead-phone">
          <span aria-hidden>📞</span> Phone Number
        </label>
        <input
          id="lead-phone"
          name="phone"
          type="tel"
          autoComplete="tel"
          inputMode="tel"
          disabled={disabled}
        />
      </div>

      <div className="lead-form__field">
        <label htmlFor="lead-email">
          <span aria-hidden>✉️</span> Email Address <span className="req">*</span>
        </label>
        <input
          id="lead-email"
          name="email"
          type="email"
          autoComplete="email"
          inputMode="email"
          required
          disabled={disabled}
        />
      </div>

      <div className="lead-form__field">
        <label htmlFor="lead-msg">Tell us about your business</label>
        <textarea id="lead-msg" name="message" rows={5} disabled={disabled} />
      </div>

      <div className="lead-form__field lead-form__field--checkbox">
        <label htmlFor="lead-opt">
          <input id="lead-opt" name="smsOptIn" type="checkbox" disabled={disabled} />
          <span>
            Check this box to receive messages from our sales team. Message frequency
            varies, and data rates may apply.
          </span>
        </label>
      </div>

      {/* Honeypot — keep visually hidden + outside the tab order. */}
      <div className="lead-form__hp" aria-hidden="true">
        <label>
          Leave this empty
          <input type="text" name="company_website" tabIndex={-1} autoComplete="off" />
        </label>
      </div>

      {state.kind === 'error' ? (
        <div className="lead-form__error" role="alert">
          {state.message}
        </div>
      ) : null}

      <button type="submit" className="lead-form__submit" disabled={disabled}>
        {disabled ? 'Sending…' : 'Submit'}
      </button>

      <p className="lead-form__legal">GDPR | CAN-Spam compliant</p>
    </form>
  )
}
