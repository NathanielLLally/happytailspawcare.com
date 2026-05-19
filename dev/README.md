# happytailspawcare.com — Payload + Next.js mirror

A Payload CMS 3 + Next.js 15 mirror of the WordPress site at
https://happytailspawcare.com. Content was imported from `../data/wp.sql`
(MariaDB dump) and `../server_root/wp-content/uploads/` (media files), and
each page's rendered HTML + per-page Spectra CSS was scraped from the live
site so the Gutenberg / Spectra (UAGB) blocks display correctly.

## Stack

- Next.js 15.4 (App Router)
- Payload CMS 3.84 with the SQLite adapter
- React 19, sharp for image processing

## Quick start

```bash
npm install
cp .env.example .env   # already populated with a random PAYLOAD_SECRET
npm run dev
```

Open http://localhost:3000 for the public site and http://localhost:3000/admin
for Payload. On first visit `/admin` will prompt you to create an admin user.

## Importing / refreshing content

```bash
# 1. Extract the WP dump into JSON (idempotent, fast)
python3 scripts/extract-wp.py

# 2. Import media, pages, posts, taxonomies, globals into Payload
npm run import:wp

# 3. Re-fetch each page from the live site to capture per-page Spectra CSS
#    (the SQL post_content alone is missing the dynamic block-id stylesheet)
npm run scrape:live

# (Optional) Just re-seed the home page body from the live render:
npm run seed:home
```

Set `ADMIN_EMAIL` and `ADMIN_PASSWORD` env vars before running `import:wp` if
you want the script to create the first admin for you; otherwise create it
through `/admin`.

## How dynamic content works

For each Page, three fields are populated from the live render:

- `rawHtml` — the `<div class="wp-site-blocks">` body content
- `inlineStyles` — `<style id="uagb-style-frontend-*">` + global presets
- `headExtras` — every `<link rel="stylesheet">` and `<script>` from the
  live `<head>` that isn't analytics/SEO

The frontend `<HtmlContent>` component renders `headExtras` into a hidden
`<div>` (so the browser fetches CSS and external `<script src>` normally)
and then re-creates every inline `<script>` inside `rawHtml` after mount,
since `innerHTML` doesn't execute scripts by spec. That makes the WP
Interactivity API hamburger, Swiper carousels, the GS logo slider, Google
Charts on `/pricing`, etc. all behave like on the live site.

Cross-origin loads from `happytailspawcare.com/wp-content/...` Just Work
for CSS and `<script src>` because there's no fetch-API or CORS read
involved. If the live origin goes away the imported pages will lose those
assets — at that point you'd cache `/wp-content/plugins/*` and `/wp-content/uploads/uag-plugin/*` locally.

## Known limitations

- **Page edits in the admin won't update the Spectra block CSS.** The
  `inlineStyles` and `headExtras` fields are snapshots of the live render;
  introducing a new UAGB block ID in `rawHtml` won't have a matching rule.
  Re-run `npm run scrape:live` after major content changes.
- **Fonts.** The site uses Inter via WP's font presets. We import the CSS
  variables but not the font file itself; pages fall back to the system
  sans-serif. Add `next/font/google` Inter if you want to match exactly.
- **Forms (Forminator / NF).** Submission endpoints point at the live
  WordPress `admin-ajax.php`. They'll still POST to the live site from the
  mirror, which may or may not be what you want.

## Project layout

```
src/
  app/
    (frontend)/       public site routes (/, /[slug], /blog, /posts/[slug])
    (payload)/        Payload admin + REST/GraphQL routes
  collections/        Pages, Posts, Media, Categories, Tags, Users
  globals/            SiteSettings, Header, Footer
  payload.config.ts   Payload root config
scripts/
  extract-wp.py       Parse data/wp.sql → JSON
  import-wp.ts        Local-API importer (idempotent)
  scrape-live-pages.ts  Pull each page's body + inline styles from live
  seed-home-from-live.ts
public/
  spectra-assets/     Theme & UAGB CSS fetched once from the live site
media/                Payload's media staticDir (file uploads land here)
```
