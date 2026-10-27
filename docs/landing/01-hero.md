---
view: components.packages.package-hero
badges:
  - label: Laravel Package
    color: blue
  - label: "{package.latestVersionLabel}"
    color: green
  - label: "{package.license} License"
    color: purple
  - label: "{package.downloadsShort}+ downloads"
    color: blue
eyebrow: "{package.name}"
title: Clean URLs,
highlight: one line away
buttons:
  - label: View Full Documentation
    href: "{card.docsUrl}"
    style: primary
    icon: arrow-right
  - label: View on GitHub
    href: "{package.githubUrl}"
    style: ghost
    external: true
install: "{card.install}"
labels:
  copy: Copy
  copied: Copied!
---

Slugs for Eloquent, zero setup. Add `#[Slug(source: 'title')]` to your model using the `Sluggable` trait and `Hello World` becomes `hello-world`, then `hello-world-2`. Define multiple slug columns with repeatable attributes or `sluggable()`. Existing property and callable source configuration stays supported.
