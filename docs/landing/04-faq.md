---
view: components.home.faq
badge: FAQ
title: "{package.name}"
highlight: Questions
subtitle: "Quick answers about installing and using {package.name}."
---

## What is {package.name}?

{card.description} It's a free, open-source {technology.name} package by Pharaonic.

## How do I install {package.name}?

Run `composer require {package.composer}` in your project's root directory.

## What does {package.name} require?

The latest release requires {package.requiresText}.

## Can a model have more than one slug?

Yes. Define a `sluggable()` method that returns `column => source` pairs, such as `'slug' => 'title'` and `'seo_slug' => 'seo_title'`. There's no limit, and each column is generated and kept unique on its own.

## Can a slug come from a relation?

Yes, through a callable source: `'category_slug' => fn ($model) => $model->category?->name ?? ''`. A plain string such as `'category.name'` is always read as a direct attribute, never as a relation path.

## Will my slug change when I edit the title?

Not by default. `on_update` is `false`, so links stay stable. Enable it globally or per slug to regenerate the slug when its source changes. A slug you set yourself is never overwritten.

## Does it support Arabic and other non-Latin titles?

Yes. Slugs keep Unicode letters by default (`مرحبا-بالعالم`). Set `ascii_only` to `true` to transliterate them to ASCII instead.

## Is {package.name} free to use?

Yes. {package.name} is open source under the {package.license} license, so you can use it in personal and commercial projects.

## Where can I find the {package.name} documentation?

Read the [{package.name} documentation]({package.docsUrl}) for setup, configuration, and usage examples.

## How do I report a bug or contribute to {package.name}?

Open an issue or a pull request on [GitHub]({package.githubUrl}), or ask in the [Pharaonic Discord](https://discord.gg/XQG9RhvEvf).
