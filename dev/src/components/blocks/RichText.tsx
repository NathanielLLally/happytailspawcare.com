import { RichText as PayloadRichText } from '@payloadcms/richtext-lexical/react'

export type RichTextData = {
  blockType: 'richText'
  content?: any
  width?: 'normal' | 'wide' | 'full'
  alignment?: 'left' | 'center'
}

export function RichText({ data }: { data: RichTextData }) {
  if (!data.content) return null
  return (
    <section
      className={`block-richtext block-richtext--${data.width ?? 'normal'} block-richtext--${data.alignment ?? 'left'}`}
    >
      <div className="container">
        <PayloadRichText data={data.content} />
      </div>
    </section>
  )
}
