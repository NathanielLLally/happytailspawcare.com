/**
 * Dispatch a Payload `blocks` array to the matching React renderer.
 * If a blockType is unknown (e.g. someone added a block in the admin
 * before the renderer landed) we render nothing rather than crash.
 */
import { Hero, type HeroData } from './Hero'
import { RichText, type RichTextData } from './RichText'
import { MediaText, type MediaTextData } from './MediaText'
import { FeatureGrid, type FeatureGridData } from './FeatureGrid'
import { LogoTicker } from './LogoTicker'
import { CallToAction, type CallToActionData } from './CallToAction'
import { LeadFormBlock, type LeadFormBlockData } from './LeadFormBlock'
import { HtmlBlockRender, type HtmlBlockData } from './HtmlBlock'

export type PageBlock =
  | HeroData
  | RichTextData
  | MediaTextData
  | FeatureGridData
  | (LogoTickerProps & { blockType: 'logoTicker' })
  | CallToActionData
  | LeadFormBlockData
  | HtmlBlockData

type LogoTickerProps = {
  eyebrow?: string
  speedSeconds?: number
  logos?: any[]
}

export function PageBlocks({
  blocks,
  sourcePage,
}: {
  blocks: any[]
  sourcePage?: string
}) {
  if (!Array.isArray(blocks) || blocks.length === 0) return null
  return (
    <>
      {blocks.map((block, idx) => {
        const key = block.id ?? `${block.blockType}-${idx}`
        switch (block.blockType) {
          case 'hero':
            return <Hero key={key} data={block} />
          case 'richText':
            return <RichText key={key} data={block} />
          case 'mediaText':
            return <MediaText key={key} data={block} />
          case 'featureGrid':
            return <FeatureGrid key={key} data={block} />
          case 'logoTicker':
            return <LogoTicker key={key} data={block} />
          case 'cta':
            return <CallToAction key={key} data={block} />
          case 'leadForm':
            return <LeadFormBlock key={key} data={block} sourcePage={sourcePage} />
          case 'html':
            return <HtmlBlockRender key={key} data={block} />
          default:
            return null
        }
      })}
    </>
  )
}
