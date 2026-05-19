#!/usr/bin/env python3
"""Extract WordPress content from a MariaDB dump into JSON the Payload
importer can consume.

Output: scripts/wp-extract/{posts.json,pages.json,media.json,navigation.json,
        options.json,categories.json,tags.json,relations.json}

Only the small set of WP tables we actually need are parsed: wp_posts,
wp_postmeta, wp_terms, wp_term_taxonomy, wp_term_relationships, wp_options.
Revisions, global_styles, templates, and other theme/plugin junk are
filtered out.
"""
from __future__ import annotations
import json
import os
import re
import sys
from html import unescape

ROOT = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '..'))
DUMP = os.path.join(ROOT, 'data', 'wp.sql')
OUT = os.path.join(os.path.dirname(__file__), 'wp-extract')
os.makedirs(OUT, exist_ok=True)


def _decode_escape(nxt: str) -> str:
    return {
        'n': '\n', 't': '\t', 'r': '\r', '0': '\0',
        'b': '\b', 'Z': '\x1a',
        '\\': '\\', "'": "'", '"': '"',
    }.get(nxt, nxt)


def parse_tuples(payload: str):
    """Parse `(...),(...),(...)` MySQL VALUES payload into list[list[str|None]].

    Strings are single-quoted with backslash escapes (MariaDB dump style).
    NULL is the literal token `NULL`. Numbers are returned as strings.
    """
    i = 0
    n = len(payload)
    out: list[list] = []
    while i < n:
        while i < n and payload[i] in ' \t\r\n,':
            i += 1
        if i >= n:
            break
        if payload[i] != '(':
            i += 1
            continue
        i += 1
        fields: list = []
        cur: list[str] = []
        in_str = False
        token_started = False
        while i < n:
            c = payload[i]
            if in_str:
                if c == '\\' and i + 1 < n:
                    cur.append(_decode_escape(payload[i + 1]))
                    i += 2
                    continue
                if c == "'":
                    in_str = False
                    i += 1
                    continue
                cur.append(c)
                i += 1
                continue
            else:
                if c == "'":
                    in_str = True
                    cur = []
                    token_started = True
                    i += 1
                    continue
                if c == ',':
                    if token_started:
                        v = ''.join(cur)
                        # NULL literal
                        if v.strip().upper() == 'NULL' and not cur:
                            fields.append(None)
                        elif v == 'NULL':
                            fields.append(None)
                        else:
                            fields.append(v if cur else None)
                    else:
                        fields.append(None)
                    cur = []
                    token_started = False
                    i += 1
                    continue
                if c == ')':
                    if token_started:
                        v = ''.join(cur)
                        if v == 'NULL':
                            fields.append(None)
                        else:
                            fields.append(v)
                    elif not fields and not cur:
                        pass
                    else:
                        fields.append(None)
                    out.append(fields)
                    i += 1
                    break
                if c in ' \t\r\n':
                    i += 1
                    continue
                # numeric / unquoted token (NULL, numbers)
                token_started = True
                cur.append(c)
                i += 1
                continue
    return out


def read_insert_rows(sql: str, table: str):
    """Yield all rows from every `INSERT INTO `<table>` VALUES ...;` statement."""
    pattern = re.compile(rf"INSERT INTO `{re.escape(table)}` VALUES\s*(.+?);\s*\n", re.DOTALL)
    rows = []
    for m in pattern.finditer(sql):
        rows.extend(parse_tuples(m.group(1)))
    return rows


def main() -> int:
    with open(DUMP, 'r', encoding='utf-8', errors='replace') as f:
        sql = f.read()

    # wp_posts columns (matches CREATE TABLE order in this dump)
    POST_COLS = ['ID', 'post_author', 'post_date', 'post_date_gmt', 'post_content',
                 'post_title', 'post_excerpt', 'post_status', 'comment_status',
                 'ping_status', 'post_password', 'post_name', 'to_ping', 'pinged',
                 'post_modified', 'post_modified_gmt', 'post_content_filtered',
                 'post_parent', 'guid', 'menu_order', 'post_type', 'post_mime_type',
                 'comment_count']

    raw_posts = read_insert_rows(sql, 'wp_posts')
    posts = [dict(zip(POST_COLS, r)) for r in raw_posts if len(r) >= len(POST_COLS)]
    print(f"wp_posts: {len(posts)} rows", file=sys.stderr)

    # Filter to types we care about
    keep_types = {'page', 'post', 'attachment', 'nav_menu_item', 'wp_navigation'}
    posts = [p for p in posts if p['post_type'] in keep_types]
    # exclude revisions/autosaves regardless
    posts = [p for p in posts if p['post_status'] != 'inherit' or p['post_type'] == 'attachment']
    print(f"wp_posts kept: {len(posts)}", file=sys.stderr)

    # wp_postmeta
    META_COLS = ['meta_id', 'post_id', 'meta_key', 'meta_value']
    raw_meta = read_insert_rows(sql, 'wp_postmeta')
    meta = [dict(zip(META_COLS, r)) for r in raw_meta if len(r) >= len(META_COLS)]
    meta_by_post: dict[int, dict[str, list[str]]] = {}
    for m in meta:
        try:
            pid = int(m['post_id'])
        except (TypeError, ValueError):
            continue
        meta_by_post.setdefault(pid, {}).setdefault(m['meta_key'] or '', []).append(m['meta_value'] or '')

    # wp_terms / wp_term_taxonomy / wp_term_relationships
    TERM_COLS = ['term_id', 'name', 'slug', 'term_group']
    TT_COLS = ['term_taxonomy_id', 'term_id', 'taxonomy', 'description', 'parent', 'count']
    TR_COLS = ['object_id', 'term_taxonomy_id', 'term_order']

    terms = [dict(zip(TERM_COLS, r)) for r in read_insert_rows(sql, 'wp_terms') if len(r) >= len(TERM_COLS)]
    term_taxonomy = [dict(zip(TT_COLS, r)) for r in read_insert_rows(sql, 'wp_term_taxonomy') if len(r) >= len(TT_COLS)]
    term_rels = [dict(zip(TR_COLS, r)) for r in read_insert_rows(sql, 'wp_term_relationships') if len(r) >= len(TR_COLS)]

    # wp_options — just a handful
    OPT_COLS = ['option_id', 'option_name', 'option_value', 'autoload']
    options_rows = [dict(zip(OPT_COLS, r)) for r in read_insert_rows(sql, 'wp_options') if len(r) >= 3]
    wanted_opts = {'siteurl', 'blogname', 'blogdescription', 'show_on_front',
                   'page_on_front', 'page_for_posts', 'template', 'stylesheet',
                   'date_format', 'time_format', 'admin_email'}
    options = {o['option_name']: o['option_value'] for o in options_rows if o['option_name'] in wanted_opts}

    # Split posts by purpose
    pages = [p for p in posts if p['post_type'] == 'page' and p['post_status'] == 'publish']
    blog_posts = [p for p in posts if p['post_type'] == 'post' and p['post_status'] == 'publish']
    attachments = [p for p in posts if p['post_type'] == 'attachment']
    nav_items = [p for p in posts if p['post_type'] == 'nav_menu_item']
    wp_navs = [p for p in posts if p['post_type'] == 'wp_navigation']

    # Categories / tags
    categories = []
    tags = []
    nav_menus = []
    for tt in term_taxonomy:
        try:
            term_id = int(tt['term_id'])
        except (TypeError, ValueError):
            continue
        term = next((t for t in terms if int(t['term_id']) == term_id), None)
        if not term:
            continue
        rec = {'wpTermId': term_id, 'name': term['name'], 'slug': term['slug']}
        if tt['taxonomy'] == 'category':
            categories.append(rec)
        elif tt['taxonomy'] == 'post_tag':
            tags.append(rec)
        elif tt['taxonomy'] == 'nav_menu':
            nav_menus.append(rec)

    # Post → category/tag relations
    relations: dict[int, dict[str, list[int]]] = {}
    for tr in term_rels:
        try:
            oid = int(tr['object_id']); ttid = int(tr['term_taxonomy_id'])
        except (TypeError, ValueError):
            continue
        tt = next((t for t in term_taxonomy if int(t['term_taxonomy_id']) == ttid), None)
        if not tt:
            continue
        try:
            tid = int(tt['term_id'])
        except (TypeError, ValueError):
            continue
        rel = relations.setdefault(oid, {'categories': [], 'tags': [], 'nav_menus': []})
        if tt['taxonomy'] == 'category':
            rel['categories'].append(tid)
        elif tt['taxonomy'] == 'post_tag':
            rel['tags'].append(tid)
        elif tt['taxonomy'] == 'nav_menu':
            rel['nav_menus'].append(tid)

    def page_record(p):
        pid = int(p['ID'])
        m = meta_by_post.get(pid, {})
        thumb = m.get('_thumbnail_id', [None])[0]
        return {
            'wpId': pid,
            'slug': p['post_name'] or f"page-{pid}",
            'title': unescape(p['post_title'] or ''),
            'excerpt': p['post_excerpt'] or '',
            'rawHtml': p['post_content'] or '',
            'date': p['post_date'],
            'modified': p['post_modified'],
            'originalUrl': p['guid'] or '',
            'parent': int(p['post_parent']) if p['post_parent'] and p['post_parent'] != '0' else None,
            'featuredImageWpId': int(thumb) if thumb and str(thumb).isdigit() else None,
            'status': 'published' if p['post_status'] == 'publish' else 'draft',
        }

    def post_record(p):
        rec = page_record(p)
        wp_id = rec['wpId']
        cats = relations.get(wp_id, {}).get('categories', [])
        tg = relations.get(wp_id, {}).get('tags', [])
        rec['categories'] = cats
        rec['tags'] = tg
        return rec

    def media_record(p):
        pid = int(p['ID'])
        m = meta_by_post.get(pid, {})
        attached_file = m.get('_wp_attached_file', [''])[0]
        alt = m.get('_wp_attachment_image_alt', [''])[0]
        return {
            'wpId': pid,
            'title': unescape(p['post_title'] or ''),
            'alt': alt,
            'caption': p['post_excerpt'] or '',
            'description': p['post_content'] or '',
            'mimeType': p['post_mime_type'] or '',
            # path relative to wp-content/uploads, e.g. "2025/11/foo.png"
            'uploadPath': attached_file,
            # full WP URL it was served at
            'originalUrl': p['guid'] or '',
            'date': p['post_date'],
        }

    pages_out = [page_record(p) for p in pages]
    posts_out = [post_record(p) for p in blog_posts]
    media_out = [media_record(p) for p in attachments]

    # WP block-based navigation (post_type=wp_navigation) — content is a
    # Gutenberg block tree, the simplest practical approach is to scrape link
    # blocks out of it. Each wp:navigation-link block has url + label attrs.
    nav_link_re = re.compile(
        r'<!--\s*wp:navigation-link\s*(\{[^}]*\})\s*/?-->',
        re.MULTILINE,
    )
    navigations_out = []
    for n in wp_navs:
        items = []
        for m in nav_link_re.finditer(n['post_content'] or ''):
            try:
                attrs = json.loads(m.group(1))
            except json.JSONDecodeError:
                continue
            label = attrs.get('label') or ''
            url = attrs.get('url') or ''
            if label or url:
                items.append({'label': unescape(label), 'href': url})
        navigations_out.append({
            'wpId': int(n['ID']),
            'title': n['post_title'] or '',
            'slug': n['post_name'] or '',
            'items': items,
        })

    # Classic nav_menu_item content (older menu storage) — keep too
    classic_nav = []
    for it in nav_items:
        pid = int(it['ID'])
        m = meta_by_post.get(pid, {})
        url = m.get('_menu_item_url', [''])[0]
        title = unescape(it['post_title'] or '')
        if not title:
            # fall back to linked object
            obj_id = m.get('_menu_item_object_id', [''])[0]
            obj_type = m.get('_menu_item_object', [''])[0]
            if obj_id and obj_type:
                title = f"{obj_type}:{obj_id}"
        classic_nav.append({
            'wpId': pid,
            'menuOrder': int(it['menu_order'] or 0),
            'title': title,
            'href': url,
            'parent': m.get('_menu_item_menu_item_parent', [''])[0],
            'wpMenuRels': relations.get(pid, {}).get('nav_menus', []),
        })

    payload = {
        'options.json': options,
        'pages.json': pages_out,
        'posts.json': posts_out,
        'media.json': media_out,
        'categories.json': categories,
        'tags.json': tags,
        'navigation.json': {
            'block_navigations': navigations_out,
            'classic_menus': nav_menus,
            'classic_items': classic_nav,
        },
    }

    for name, data in payload.items():
        path = os.path.join(OUT, name)
        with open(path, 'w', encoding='utf-8') as f:
            json.dump(data, f, indent=2, ensure_ascii=False)
        print(f"wrote {path} ({len(data) if isinstance(data, list) else 'object'})", file=sys.stderr)

    return 0


if __name__ == '__main__':
    sys.exit(main())
