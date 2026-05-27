'use client'

import { useState, type FormEvent } from 'react'
import Script from 'next/script'

type State =
  | { kind: 'idle' }
  | { kind: 'submitting' }
  | { kind: 'success' }
  | { kind: 'error'; message: string }

const SERVICE_TYPES = [
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
]

const SPECULATION_MODELS = [
  'Conservative',
  'Low-Key Flex',
  'Reliable Prediction',
  'Baller Bracket',
  'Well-Oiled Machine',
]

const LEADS_OPTIONS = ['0', '10', '20', '40']

const RECAPTCHA_SITE_KEY = '6LeCETssAAAAAATdYsv2aKBxCGwFeOKwmutOj1zr'

export function BusinessModelForm({ sourcePage = '/your-business-model' }: { sourcePage?: string }) {
  const [state, setState] = useState<State>({ kind: 'idle' })
  const [step, setStep] = useState(1)
  
  const [formData, setFormData] = useState({
    serviceType: '',
    speculationModel: SPECULATION_MODELS[0],
    leadsPerMonth: '20',
    activeClients: '10',
    name: '',
    email: '',
    phone: '',
    smsOptIn: false,
  })

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value, type } = e.target
    const val = type === 'checkbox' ? (e.target as HTMLInputElement).checked : value
    setFormData((prev) => ({ ...prev, [name]: val }))
  }

  const isStep1Complete = 
    formData.serviceType !== '' && 
    formData.speculationModel !== '' && 
    formData.leadsPerMonth !== '' && 
    formData.activeClients !== ''

  const isStep2Complete = 
    formData.name.trim() !== '' && 
    formData.email.trim() !== ''

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault()
    const form = event.currentTarget
    const data = new FormData(form)

    // Honeypot
    if ((data.get('company_website') as string)?.trim()) {
      setState({ kind: 'success' })
      return
    }

    const payload: any = {
      name: formData.name.trim(),
      phone: formData.phone.trim(),
      email: formData.email.trim(),
      serviceType: [formData.serviceType],
      speculationModel: formData.speculationModel,
      leadsPerMonth: formData.leadsPerMonth,
      activeClients: Number(formData.activeClients),
      smsOptIn: formData.smsOptIn,
      meta: { sourcePage },
      captchaToken: '',
    }

    setState({ kind: 'submitting' })

    try {
      // Get reCAPTCHA token
      if (typeof (window as any).grecaptcha !== 'undefined') {
        payload.captchaToken = await (window as any).grecaptcha.execute(RECAPTCHA_SITE_KEY, {
          action: 'submit',
        })
      }

      // 1. Submit to Payload
      const res = await fetch('/api/leads', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      })
      if (!res.ok) {
        const body = await res.json().catch(() => ({}))
        throw new Error(body?.message || `Submission failed (HTTP ${res.status})`)
      }

      setState({ kind: 'success' })
      form.reset()
      window.location.href = 'https://script.google.com/macros/s/AKfycbwJXeC4vPpjUAZGqNQ-_qq8cKTB5g5tSSIyvfDzOchjxNv9V_Z4auBwC-adljfYQ2m6/exec?action=update'
    } catch (e: any) {
      setState({ kind: 'error', message: e?.message || 'Something went wrong.' })
    }
  }

  if (state.kind === 'success') {
    return (
      <div className="lead-form lead-form--success" role="status">
        <h3>Success! Redirecting you to pricing...</h3>
      </div>
    )
  }

  const disabled = state.kind === 'submitting'

  return (
    <>
      <Script
        src={`https://www.google.com/recaptcha/api.js?render=${RECAPTCHA_SITE_KEY}`}
        strategy="afterInteractive"
      />
      <form className="lead-form business-model-form" onSubmit={onSubmit} noValidate>
        {step === 1 && (
          <div className="form-step">
            <h3 className="form-step__title">Step 1: Business Details</h3>
            
            <div className="lead-form__field">
              <label htmlFor="service-type">Pet Service Type</label>
              <select 
                id="service-type" 
                name="serviceType" 
                value={formData.serviceType} 
                onChange={handleChange}
                disabled={disabled}
              >
                <option value="">Select a service type...</option>
                {SERVICE_TYPES.map((type) => (
                  <option key={type} value={type}>{type}</option>
                ))}
              </select>
            </div>

            <div className="lead-form__field">
              <label htmlFor="speculation-model">Speculation Model</label>
              <select 
                id="speculation-model" 
                name="speculationModel" 
                value={formData.speculationModel}
                onChange={handleChange}
                disabled={disabled}
              >
                {SPECULATION_MODELS.map((model) => (
                  <option key={model} value={model}>{model}</option>
                ))}
              </select>
            </div>

            <div className="form-row">
              <div className="lead-form__field">
                <label>Incoming Leads per Month</label>
                <div className="form-radio-group">
                  {LEADS_OPTIONS.map((opt) => (
                    <label key={opt} className="radio-label">
                      <input 
                        type="radio" 
                        name="leadsPerMonth" 
                        value={opt} 
                        checked={formData.leadsPerMonth === opt} 
                        onChange={handleChange}
                        disabled={disabled} 
                      />
                      <span>{opt}</span>
                    </label>
                  ))}
                </div>
              </div>

              <div className="lead-form__field">
                <label htmlFor="active-clients">Current Active Clients</label>
                <input
                  id="active-clients"
                  name="activeClients"
                  type="number"
                  min="0"
                  max="300"
                  value={formData.activeClients}
                  onChange={handleChange}
                  disabled={disabled}
                />
              </div>
            </div>

            {isStep1Complete && (
              <div className="form-footer-actions">
                <button type="button" className="lead-form__submit" onClick={() => setStep(2)}>
                  See What We Deliver →
                </button>
                <div className="recaptcha-placeholder">
                  <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" alt="reCAPTCHA" width="24" height="24" />
                  <span>protected by reCAPTCHA</span>
                </div>
              </div>
            )}
          </div>
        )}

        {step === 2 && (
          <div className="form-step">
            <h3 className="form-step__title">Step 2: Contact Information</h3>
            
            <div className="lead-form__field">
              <label htmlFor="lead-name">
                <span aria-hidden>👤</span> Full Name <span className="req">*</span>
              </label>
              <input 
                id="lead-name" 
                name="name" 
                type="text" 
                autoComplete="name" 
                required 
                value={formData.name}
                onChange={handleChange}
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
                value={formData.email}
                onChange={handleChange}
                disabled={disabled}
              />
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
                value={formData.phone}
                onChange={handleChange}
                disabled={disabled}
              />
            </div>

            <div className="lead-form__field lead-form__field--checkbox">
              <label htmlFor="lead-opt">
                <input 
                  id="lead-opt" 
                  name="smsOptIn" 
                  type="checkbox" 
                  checked={formData.smsOptIn}
                  onChange={handleChange}
                  disabled={disabled} 
                />
                <span>
                  Check this box to receive messages from our sales team. Message frequency
                  varies, and data rates may apply.
                </span>
              </label>
            </div>

            {isStep2Complete && (
              <div className="form-footer-actions">
                <div className="form-buttons">
                  <button type="button" className="lead-form__submit lead-form__submit--secondary" onClick={() => setStep(1)} disabled={disabled}>
                    ← Back
                  </button>
                  <button type="submit" className="lead-form__submit" disabled={disabled}>
                    {disabled ? 'Sending…' : 'Calculate & View Pricing'}
                  </button>
                </div>
                <div className="recaptcha-placeholder">
                  <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" alt="reCAPTCHA" width="24" height="24" />
                  <span>protected by reCAPTCHA</span>
                </div>
              </div>
            )}
          </div>
        )}

        {/* Honeypot */}
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

        <p className="lead-form__legal">GDPR | CAN-Spam compliant</p>
      </form>
    </>
  )
}
