:::badges
- Laravel Package {color=blue}
- {release.label} {color=green}
- {package.license} License {color=purple}
:::

# Laravel Sluggable

Laravel Sluggable generates URL-friendly slugs for your Eloquent models. Add the `Sluggable` trait and one `$sluggable` property, and the `slug` column fills itself when a record is created. When you need more, a `sluggable()` method can fill any number of slug columns, each from its own attribute, relation or computed value. The text itself is slugified by [PHP Slugify](https://github.com/Pharaonic/php-slugify), so Unicode, transliteration and language rules come from one place.

:::features
### One-Line Setup {icon="bolt"}
`protected $sluggable = 'title';` fills the `slug` column on create, using your global config.

### Unlimited Slug Columns {icon="grid"}
Return `column => source` pairs from `sluggable()` to fill `slug`, `seo_slug`, `category_slug` and more.

### Callable Sources {icon="code"}
Use `fn ($model) => ...` to build a slug from a relation, an accessor or a computed value.

### Unique Per Column {icon="badge-check"}
Collisions get `-2`, `-3`, ... suffixes, found with one query and kept within `max_length`.

### Stable Links {icon="lock"}
Manual slugs are never overwritten, and existing slugs only change when you enable `on_update`.

### Unicode & ASCII {icon="translate"}
Arabic and other scripts stay readable by default. Switch on `ascii_only` to transliterate.
:::

:::info Quick Tip
Keep the unique index that `$table->sluggable()` creates. The package picks a free slug before saving, but only the database can guarantee uniqueness when two requests save at the same time.
:::
