import { LeadForm } from '@/components/LeadForm'

export type LeadFormBlockData = {
  blockType: 'leadForm'
  heading?: string
  intro?: string
}

export function LeadFormBlock({
  data,
  sourcePage,
}: {
  data: LeadFormBlockData
  sourcePage?: string
}) {
  return (
    <section className="block-leadform">
      <div className="container lead-form-wrap">
        {data.heading ? <h2 className="block-leadform__heading">{data.heading}</h2> : null}
        {data.intro ? <p className="block-leadform__intro">{data.intro}</p> : null}
        <LeadForm sourcePage={sourcePage} />
      </div>
    </section>
  )
}
