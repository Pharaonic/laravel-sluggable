## Configuration

The config lives in `config/pharaonic/sluggable.php` once published, and is read from `pharaonic.sluggable`. These values apply to every slug unless a [per-slug option](#per-slug-options) overrides them.

```php title="config/pharaonic/sluggable.php"
return [
    'separator' => '-',
    'unique' => true,
    'on_create' => true,
    'on_update' => false,
    'include_trashed' => false,
    'max_length' => 255,
    'ascii_only' => false,
    'ascii_lang' => env('APP_LOCALE', 'en'),
];
```

### Options

| Key | Default | Description |
| --- | --- | --- |
| `separator` | `-` | Joins the words of the slug and the unique suffix. |
| `unique` | `true` | Append `-2`, `-3`, ... when the slug is already taken in its column. |
| `on_create` | `true` | Generate slugs when a model is created (only for empty slug columns). |
| `on_update` | `false` | Regenerate a slug when its source changes on update. |
| `include_trashed` | `false` | Also check soft-deleted rows when looking for a unique slug. |
| `max_length` | `255` | Maximum slug length in characters, suffix included. `null` means no limit. |
| `ascii_only` | `false` | Transliterate slugs to ASCII (`Crème brûlée` → `creme-brulee`). |
| `ascii_lang` | `APP_LOCALE` or `en` | Language passed to PHP Slugify for language-specific rules. |

### Resolution Order

Each option is resolved per slug:

```text no-copy no-line-numbers
per-slug option  →  config/pharaonic/sluggable.php  →  package default
```

The `$sluggable` property has no per-slug options, so it always uses the config.

:::info Unicode by Default
With `ascii_only` set to `false`, slugs keep non-Latin letters: `مرحبا بالعالم` becomes `مرحبا-بالعالم`. Browsers display these URLs as written and encode them automatically.
:::
