## API Reference

All methods live on the `Pharaonic\Laravel\Sluggable\Sluggable` trait.

### Model Configuration

| Member | Description |
| --- | --- |
| `protected $sluggable = 'title';` | Generate `slug` from one direct attribute, using the config. |
| `public function sluggable(): array` | Return `column => source` or `column => [options]` for any number of slugs. Takes precedence over `$sluggable`. |

### Methods

| Method | Description | Returns |
| --- | --- | --- |
| `Model::findBySlug(string $slug, array $columns = ['*'])` | First model whose `slug` matches. | `Model` or `null` |
| `Model::findBySlugOrFail(string $slug, array $columns = ['*'])` | Same, but throws when missing. | `Model` (throws `ModelNotFoundException`) |
| `Model::whereSlug(string $slug)` | Query scope on the `slug` column. | `Builder` |
| `$model->slug_with_key` | `{key}-{slug}`, using the parent key for `*Translation` models. | `string` |

`findBySlug`, `findBySlugOrFail`, `whereSlug` and `slug_with_key` use the `slug` column. For other slug columns, query them directly: `Post::where('seo_slug', $slug)->first()`.

### Slug Options

| Option | Default | Description |
| --- | --- | --- |
| `source` | — | Attribute name or callable. Per-slug only. |
| `separator` | `-` | Word and suffix separator. |
| `unique` | `true` | Append `-2`, `-3`, ... on collisions. |
| `on_create` | `true` | Generate on create (config only). |
| `on_update` | `false` | Regenerate when the source changes. |
| `include_trashed` | `false` | Include soft-deleted rows in the uniqueness check. |
| `max_length` | `255` | Maximum length, suffix included. `null` for no limit. |
| `ascii_only` | `false` | Transliterate to ASCII (config only). |
| `ascii_lang` | `APP_LOCALE` / `en` | Language hint for PHP Slugify (config only). |

### Schema Macro

| Macro | Creates |
| --- | --- |
| `$table->sluggable(string $column = 'slug')` | `string($column)->nullable()->unique()` |

### Blade Directive

| Directive | Output |
| --- | --- |
| `@slug($value, $separator = '-', $asciiOnly = false, $language = 'en')` | The slugified value |

### Publish Tags

| Tag | Publishes |
| --- | --- |
| `sluggable-config`, `laravel-sluggable`, `pharaonic`, `pharaonic-config` | `config/pharaonic/sluggable.php` |
