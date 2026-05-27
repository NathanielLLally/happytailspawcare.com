/**
 * Strip the scraped Forminator form out of the imported /learn-more
 * rawHtml and leave a marker behind. The route renders the page split
 * around the marker with our React <LeadForm /> in its place.
 *
 * The live form is a Forminator AJAX form pointing at the WordPress
 * admin-ajax.php endpoint, which won't work from this Next app (origin
 * + nonce + plugin scripts). Replacing it with a Payload-backed form
 * preserves the same user-visible fields and visual context.
 */
export const LEAD_FORM_MARKER = '<!--HTPC_LEAD_FORM-->'

const FORMINATOR_BLOCK_RE =
  /(?:<div[^>]*\bforminator-ui[^>]*>[\s\S]*?<\/form>[\s\S]*?<\/div>(?:\s*<\/div>)?|\[forminator_form[^\]]*\])/g

export function stripForminator(html: string, marker = LEAD_FORM_MARKER): {
  before: string
  after: string
} {
  if (!html) return { before: '', after: '' }
  const replaced = html.replace(FORMINATOR_BLOCK_RE, marker)
  const idx = replaced.indexOf(marker)
  if (idx === -1) {
    // No form found — return the original HTML in `before`, empty `after`.
    return { before: html, after: '' }
  }
  return {
    before: replaced.slice(0, idx),
    after: replaced.slice(idx + marker.length),
  }
}
