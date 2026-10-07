---
view: components.packages.features
variant: compact
badge: Key Features
title: Everything you need for model slugs
subtitle: Add the trait, name the source column, and every new record gets a unique slug.
items:
  - icon: bolt
    title: One-Line Setup
    text: Add `protected $sluggable = 'title';` and the `slug` column fills itself on create.
  - icon: grid
    title: Unlimited Slug Columns
    text: Return `column => source` from `sluggable()` to fill `slug`, `seo_slug` and more, independently.
  - icon: code
    title: Callable Sources
    text: Build a slug from a relation, an accessor or any computed value with `fn ($model) => ...`.
  - icon: badge-check
    title: Always Unique
    text: Collisions become `hello-2`, `hello-3`, found with one query per column and within `max_length`.
  - icon: lock
    title: Manual Slugs Respected
    text: A slug you set yourself is never overwritten, and existing slugs stay stable unless you enable `on_update`.
  - icon: translate
    title: Unicode & ASCII
    text: Keeps Arabic and other scripts by default, or transliterates to ASCII with one config switch.
---
