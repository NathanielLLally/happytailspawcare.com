import { HtmlContent } from '@/components/HtmlContent'

export type HtmlBlockData = { blockType: 'html'; html: string }

export function HtmlBlockRender({ data }: { data: HtmlBlockData }) {
  if (!data.html) return null
  return (
    <section className="block-html">
      <div className="container">
        <HtmlContent html={data.html} />
      </div>
    </section>
  )
}
