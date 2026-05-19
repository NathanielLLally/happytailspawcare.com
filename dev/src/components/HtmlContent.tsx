'use client'

import { useEffect, useRef } from 'react'

/**
 * Render imported HTML AND make embedded <script> tags execute.
 *
 * React's `dangerouslySetInnerHTML` injects via `innerHTML`, which is a
 * browser API that explicitly will NOT execute any <script> tags inside.
 * To run them we walk the rendered tree after mount and re-create each
 * script as a real <script> element appended to the DOM.
 *
 * Ordering: external scripts (with src) load async, but inline scripts that
 * follow run in source order — same behavior as if the HTML had been parsed
 * normally. For the Spectra / swiper / GS slider scripts that depend on
 * library scripts loaded from the WP-side head, we render `head` separately
 * BEFORE the body, so its <script src=...> tags get parsed by the browser
 * normally (without going through innerHTML) and load in time.
 */
export function HtmlContent({
  head,
  html,
}: {
  head?: string
  html: string
}) {
  const headRef = useRef<HTMLDivElement>(null)
  const bodyRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    activateScripts(headRef.current)
  }, [head])

  useEffect(() => {
    activateScripts(bodyRef.current)
  }, [html])

  return (
    <>
      {head ? (
        <div
          ref={headRef}
          style={{ display: 'none' }}
          aria-hidden
          dangerouslySetInnerHTML={{ __html: head }}
        />
      ) : null}
      <div ref={bodyRef} className="htpc-rawhtml" dangerouslySetInnerHTML={{ __html: html }} />
    </>
  )
}

function activateScripts(root: Element | null) {
  if (!root) return
  const scripts = Array.from(root.querySelectorAll('script'))
  for (const old of scripts) {
    if (old.dataset.htpcActivated === '1') continue
    const fresh = document.createElement('script')
    for (const attr of Array.from(old.attributes)) {
      fresh.setAttribute(attr.name, attr.value)
    }
    if (old.textContent) fresh.textContent = old.textContent
    fresh.dataset.htpcActivated = '1'
    old.parentNode?.replaceChild(fresh, old)
  }
}
