<p align="center"><a href="https://pharaonic.dev/packages/laravel/sluggable" target="_blank"><img src="https://raw.githubusercontent.com/Pharaonic/laravel-sluggable/7.x/docs/cover.webp"></a></p>

<p align="center">
  <a href="https://php.net" target="_blank"><img src="https://img.shields.io/static/v1?label=PHP&message=8.0&color=blue&style=flat-square" alt="PHP Version : 8.0"></a>
  <a href="https://laravel.com" target="_blank"><img src="https://img.shields.io/static/v1?label=Laravel&message=7.x&color=F05340&style=flat-square" alt="Laravel Version : 7.x"></a>
  <img src="https://img.shields.io/static/v1?label=License&message=MIT&color=brightgreen&style=flat-square" alt="License">
  <img src="https://github.com/Pharaonic/laravel-sluggable/actions/workflows/build.yml/badge.svg?branch=7.x" alt="Build">
  <br>
  <a href="https://packagist.org/packages/Pharaonic/laravel-sluggable" target="_blank"><img src="https://img.shields.io/static/v1?label=Packagist&message=pharaonic/laravel-sluggable&color=blue&logo=packagist&logoColor=white" alt="Source"></a>
  <a href="https://packagist.org/packages/pharaonic/laravel-sluggable" target="_blank"><img src="https://poser.pugx.org/pharaonic/laravel-sluggable/v" alt="Packagist Version"></a>
  <a href="https://packagist.org/packages/pharaonic/laravel-sluggable" target="_blank"><img src="https://poser.pugx.org/pharaonic/laravel-sluggable/downloads" alt="Packagist Downloads"></a>
</p>

<h3 align="center">Simple, predictable slugs for Laravel Eloquent models.</h3>
<h5 align="center">Powered by <a href="https://pharaonic.dev/packages/php/slugify" target="_blank">PHP Slugify</a>.</h5>
<br>

## Installation

```bash
composer require pharaonic/laravel-sluggable
```

## Usage

Laravel Sluggable can generate and maintain slugs automatically through Eloquent model events.

### PHP Attributes

Configure your slug directly on the model using the `Slug` attribute:

```php
use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Sluggable\Attributes\Slug;
use Pharaonic\Laravel\Sluggable\Sluggable;

#[Slug(source: 'title')]
class Post extends Model
{
    use Sluggable;
}
```

The default target column is `slug`:

```php
#[Slug(source: 'title', target: 'slug')]
```

Need more than one slug? Repeat the attribute:

```php
#[Slug(source: 'title', target: 'slug', unique: true)]
#[Slug(source: 'seo_title', target: 'seo_slug')]
class Post extends Model
{
    use Sluggable;
}
```

Available attribute options include:

`source`, `target`, `separator`, `unique`, `on_create`, `on_update`, `include_trashed`, `max_length`, `ascii_only`, and `ascii_lang`.

Omitted options inherit the package defaults.

Use `max_length: 0` when no length limit is required.

### Callable Sources

PHP Attributes are designed for simple column-based sources.

For computed values, multiple columns, relations, or custom logic, use the existing `sluggable()` method:

```php
public function sluggable(): array
{
    return [
        'slug' => [
            'source' => fn ($model) => $model->category->name.' '.$model->title,
        ],
    ];
}
```

## Configuration Priority

Laravel Sluggable keeps all configuration styles backward compatible.

When more than one style is available, the package resolves them in this order:

```text
sluggable() method
    ↓
PHP Attributes
    ↓
$sluggable property
    ↓
Package defaults
```

Definitions from different styles are not merged.

Package defaults only fill missing options for the selected configuration.

Existing `$sluggable` properties and `sluggable()` methods continue to work unchanged.

## Documentation

For the complete API, configuration options, inheritance rules, validation behavior, and advanced usage, see the [Laravel Sluggable Documentation](https://pharaonic.dev/packages/laravel/sluggable).

## Contributing

Contributions are welcome. See [CONTRIBUTING.md](CONTRIBUTING.md).

## Support & Security

See [SUPPORT.md](SUPPORT.md) and [SECURITY.md](SECURITY.md).

## License

MIT. See [LICENSE](LICENSE).
