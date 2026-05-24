import { HeroBlock } from './Hero'
import { RichTextBlock } from './RichText'
import { MediaTextBlock } from './MediaText'
import { FeatureGridBlock } from './FeatureGrid'
import { LogoTickerBlock } from './LogoTicker'
import { CallToActionBlock } from './CallToAction'
import { LeadFormBlock } from './LeadFormBlock'
import { HtmlBlock } from './HtmlBlock'

export const pageBlocks = [
  HeroBlock,
  RichTextBlock,
  MediaTextBlock,
  FeatureGridBlock,
  LogoTickerBlock,
  CallToActionBlock,
  LeadFormBlock,
  HtmlBlock,
]

export type PageBlockSlug =
  | 'hero'
  | 'richText'
  | 'mediaText'
  | 'featureGrid'
  | 'logoTicker'
  | 'cta'
  | 'leadForm'
  | 'html'
