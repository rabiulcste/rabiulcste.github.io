# [rabiul.me](https://rabiul.me)

Personal site and blog, built with **[Jekyll](https://jekyllrb.com/)** and hosted on **GitHub Pages**.

## Requirements

- **Ruby** and **[Bundler](https://bundler.io/)** (see [Using Jekyll with Bundler](https://jekyllrb.com/tutorials/using-jekyll-with-bundler/))
- **Node.js** and npm (for CSS/JS minify and image tooling)

## Setup

```sh
bundle install
npm install
```

## Local preview

```sh
npm start
```

This runs `bundle exec jekyll serve -w -l --host 0.0.0.0` (default port **4000**).  
Alternatively: `bundle exec jekyll serve`

## Build assets before deploy

Edits to **`css/rabiul-blog.css`** and **`js/site.js`** are not served until minified:

```sh
npx grunt
```

Or CSS + JS + image pass:

```sh
npm run build:assets
```

- **`npm run build:css`** — minify CSS only  
- **`npm run build:js`** — minify `site.js` → `site.min.js`  
- **`npm run build:images`** — optimize raster/SVG and refresh **`img/**/*.webp`** next to JPG/PNG sources

The live site loads **`css/rabiul-blog.min.css`** and **`js/site.min.js`** (see `_includes/head.html` and `_includes/footer.html`).

## Repository layout (high level)

| Path | Role |
|------|------|
| `_config.yml` | Jekyll config |
| `_layouts/`, `_includes/` | Templates and partials |
| `_posts/` | Blog posts |
| `css/rabiul-blog.css` | Source stylesheet (**edit this**; minify for production) |
| `js/site.js` | Site behaviour bundle (**edit this**; uglify for production) |
| `sw.js` | Service worker (precache list should match shipped assets) |
| `scripts/optimize-images.mjs` | Image + WebP sibling generation |

## License

Copyright (c) Rabiul Awal. All rights reserved.

The project was originally based on themes inspired by [Hux Blog](https://github.com/Huxpro/huxpro.github.io) and [Start Bootstrap — Clean Blog (MIT)](https://github.com/BlackrockDigital/startbootstrap-clean-blog-jekyll/); the current codebase has been heavily customized.
