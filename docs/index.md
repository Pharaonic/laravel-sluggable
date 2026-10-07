---
name: Laravel Sluggable

action:
  label: View on Packagist
  href: "{package.packagistUrl}"

views: components.packages

breadcrumbs:
  - label: Home
    href: route:home
  - label: Packages
    href: route:packages.index
  - label: "{technology.name} Packages"
    href: "url:/packages/{technology.slug}"
  - label: "{package.name}"

card:
  topic: seo
  icon: tag
  tags: slug sluggable slugify eloquent model url seo unique permalink unicode transliteration
  description: Unique slugs for Eloquent models. One property for the common case, any number of slug columns when you need more.

seo:
  title: "{package.fullName} - Eloquent Slug Package for Laravel"
  description: "{package.name} is a Laravel package that generates unique, URL-friendly slugs for Eloquent models, with multiple slug columns, callable sources and Unicode support. {package.downloadsShort}+ downloads, {package.license} licensed."
  keywords: laravel slug, laravel sluggable, eloquent slug, unique slug, slugify, seo url, permalink, unicode slug, multiple slugs
  author: Pharaonic
  images:
    - "{package.cover}"
  openGraph:
    type: website
    siteName: Pharaonic
  twitter:
    card: summary_large_image

schema:
  "@type": SoftwareSourceCode
  name: "{package.name}"
  description: "{package.name} is a Laravel package that generates unique, URL-friendly slugs for Eloquent models."
  image: "{package.cover}"
  codeRepository: "{package.githubUrl}"
  programmingLanguage: PHP
  runtimePlatform: "{technology.name}"
  version: "{package.version}"
  datePublished: "{package.publishedAt}"
  dateModified: "{package.updatedAt}"
  license: "https://opensource.org/licenses/{package.license}"
  isAccessibleForFree: true
  sameAs:
    - "{package.githubUrl}"
    - "{package.packagistUrl}"
  author:
    "@id": url:/#organization
  publisher:
    "@id": url:/#organization
  interactionStatistic:
    "@type": InteractionCounter
    interactionType: https://schema.org/DownloadAction
    userInteractionCount: "{package.downloads}"
---
