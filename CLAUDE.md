# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

The **Compassionate Schools Project (CSP)** Kadence child theme — a child of the **Kadence** parent theme (`Template: kadence` in `style.css`). This is the only custom-authored, version-controlled code for the CSP WordPress site; the surrounding WordPress install (core, plugins, uploads) is not part of this repo.

## Architecture

The theme is **hooks-only**: it ships **no template overrides**, and `style.css` is just the theme header (site CSS is managed outside this repo — the Kadence Customizer and the `custom-css-js` plugin). All behavior lives in `functions.php`. When changing markup or layout, prefer Kadence action hooks over adding template files.

`functions.php` provides:
- **`[post_list]` shortcode** (`create_post_list`) — paginated post listing for placement on any page; accepts `count` and `category` attributes. Emits `.custom-post-list` / `.post-card` markup and `paginate_links` pagination.
- **Kadence hook customizations** — `kadence_hero_header` renders a hero banner on single posts; `kadence_loop_entry_thumbnail` supplies a fallback thumbnail (`assets/images/csp-flourish-lndscp.png`) when a post has no featured image.
- **`wp_kses_allowed_html` filter (priority 999)** — whitelists `target`/`rel`/`title` on `<a>`, and `<i>`/`<span>` class + aria attributes, so `target="_blank"` and Font Awesome icon markup survive content sanitization. The high priority is intentional; keep it if you touch this filter.
- **Vimeo oEmbed sizing** (`force_vimeo_square_oembed`) — forces 540×540 embeds.
- **`.vtt` / `.txt` upload support** (`csp_allow_vtt_txt_*`) — registers mime types and passes extension validation, including the `.vtt.txt` double extension, so caption/transcript files can be uploaded to the Media Library.

There is no build step, package manager, or test suite — plain PHP plus a CDN-loaded Font Awesome (enqueued in `functions.php`).

## Notes

- Font Awesome is loaded from a CDN (`cdnjs`), not bundled.
- This theme runs inside a larger WordPress install managed with DDEV; if you're working in that full checkout, see the root `CLAUDE.md` for environment/commands.
