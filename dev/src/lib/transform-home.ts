/**
 * Server-side rewrite of the imported home rawHtml: turn each of the four
 * "service teaser" cards into a click-through to /services and replace its
 * body copy with an animated graphic. We identify them by the very specific
 * Spectra/Gutenberg class signature used on the home hero — every other
 * page uses a different border-color modifier.
 *
 * On the home each card is shaped like:
 *
 *   <div class="wp-block-uagb-container uagb-block-XXXXXXXX">
 *     <div class="wp-block-group has-border-color has-outline-border-color …" style="border-radius:16px;…">
 *       <h4>Lead Generation</h4>
 *       <p>copy…</p>
 *     </div>
 *   </div>
 *
 * The card body has no nested <div>, so a flat regex over the inner block
 * is safe.
 */
const CARD_RE =
  /(<div class="wp-block-group has-border-color has-outline-border-color[^"]*"[^>]*>)([\s\S]*?)(<\/div>)/g

const ICON = `
<span class="home-svc-card__icon" aria-hidden="true">
  <svg viewBox="0 0 64 64" width="56" height="56" fill="none" stroke="currentColor"
       stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="32" cy="32" r="22" class="home-svc-card__ring" />
    <path d="M22 32h20" class="home-svc-card__arrow-line" />
    <path d="m34 22 10 10-10 10" class="home-svc-card__arrow-head" />
  </svg>
</span>
<span class="home-svc-card__cta" aria-hidden="true">View services</span>
`

export function transformHomeHtml(html: string): string {
  if (!html) return html
  html = html.replace(CARD_RE, (full, open, inner, close) => {
    const headingOnly = inner.replace(/<p[^>]*>[\s\S]*?<\/p>/g, '')
    return `<a href="/services" class="home-svc-card-link">${open}${headingOnly}${ICON}${close}</a>`
  })
  html = transformTrustTicker(html)
  return html
}

/**
 * The "Brought to you by" trust strip is rendered by the
 * awesome-logo-carousel-block plugin as a Swiper.js paginated carousel
 * (prev/next arrows + dot pagination, page-based autoplay). Convert it
 * into a continuously-scrolling stock-ticker: prevent Swiper init by
 * stripping its `swiper` class and data-attrs, duplicate the slide list
 * so the CSS translateX loop wraps seamlessly, and let CSS do the rest.
 */
function transformTrustTicker(html: string): string {
  // Disable Swiper init by stripping the `swiper` class + data-* config.
  // The opening tag in the source has other attrs (dir, id) interleaved, so
  // match the whole <div ...alcb__carousel_container swiper...> and edit it.
  html = html.replace(
    /<div\s+([^>]*?\balcb__carousel_container\s+swiper\b[^>]*)>/,
    (_full, attrs: string) => {
      let edited = attrs.replace(
        /class="([^"]*?)alcb__carousel_container\s+swiper([^"]*)"/,
        'class="$1alcb__carousel_container htpc-ticker$2"',
      )
      edited = edited.replace(/\s+data-[a-z-]+="[^"]*"/g, '')
      return `<div ${edited} data-htpc-ticker="true">`
    },
  )

  // Duplicate the slides inside .swiper-wrapper so the loop is seamless.
  // Anchor the wrapper's close on the next sibling (.alcb__pag) — that
  // avoids any ambiguity from the nested div in each slide.
  html = html.replace(
    /(<div class="swiper-wrapper">)([\s\S]*?)(<\/div>\s*<\/div>\s*<div class="alcb__pag)/,
    (_full, open: string, slides: string, tail: string) =>
      `${open}${slides}${slides}${tail}`,
  )

  return html
}
