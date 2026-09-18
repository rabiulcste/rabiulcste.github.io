# [rabiul.me](https://rabiul.me)

Personal site and blog, built with **[Jekyll](https://jekyllrb.com/)** and hosted on **GitHub Pages**.

## Requirements

- **Ruby** and **[Bundler](https://bundler.io/)** (see [Using Jekyll with Bundler](https://jekyllrb.com/tutorials/using-jekyll-with-bundler/))
- **Node.js** and npm (only for the image tooling)

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

## Images

```sh
npm run build:images
```

Optimizes raster/SVG sources and refreshes the **`img/**/*.webp`** siblings.

CSS and JS are served as-is — there is no build step. GitHub Pages gzips them.

## Repository layout (high level)

| Path | Role |
|------|------|
| `_config.yml` | Jekyll config |
| `_layouts/`, `_includes/` | Templates and partials |
| `_posts/` | Blog posts |
| `css/rabiul-blog.css` | Stylesheet |
| `js/site.js` | Site behaviour bundle |
| `scripts/optimize-images.mjs` | Image + WebP sibling generation |

## License

Copyright (c) Rabiul Awal. All rights reserved.

The project was originally based on themes inspired by [Hux Blog](https://github.com/Huxpro/huxpro.github.io) and [Start Bootstrap — Clean Blog (MIT)](https://github.com/BlackrockDigital/startbootstrap-clean-blog-jekyll/); the current codebase has been heavily customized.
