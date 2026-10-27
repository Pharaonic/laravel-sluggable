<p align="center"><a href="https://pharaonic.dev/packages/laravel/sluggable" target="_blank"><img src="https://raw.githubusercontent.com/Pharaonic/laravel-sluggable/6.x/docs/cover.webp"></a></p>

<p align="center">
  <a href="https://php.net" target="_blank"><img src="https://img.shields.io/static/v1?label=PHP&message=8.0&color=blue&style=flat-square" alt="PHP Version : 8.0"></a>
  <a href="https://laravel.com" target="_blank"><img src="https://img.shields.io/static/v1?label=Laravel&message=6.x&color=F05340&style=flat-square" alt="Laravel Version : 6.x"></a>
  <img src="https://img.shields.io/static/v1?label=License&message=MIT&color=brightgreen&style=flat-square" alt="License">
  <img src="https://github.com/Pharaonic/laravel-sluggable/actions/workflows/build.yml/badge.svg?branch=6.x" alt="Build">
  <br>
  <a href="https://packagist.org/packages/Pharaonic/laravel-sluggable" target="_blank"><img src="https://img.shields.io/static/v1?label=Packagist&message=pharaonic/laravel-sluggable&color=blue&logo=packagist&logoColor=white" alt="Source"></a>
  <a href="https://packagist.org/packages/pharaonic/laravel-sluggable" target="_blank"><img src="https://poser.pugx.org/pharaonic/laravel-sluggable/v" alt="Packagist Version"></a>
  <a href="https://packagist.org/packages/pharaonic/laravel-sluggable" target="_blank"><img src="https://poser.pugx.org/pharaonic/laravel-sluggable/downloads" alt="Packagist Downloads"></a>
</p>

<h3 align="center">Simplest Sluggable for Laravel Eloquent models.</h3>
<h5 align="center">Powered by <a href="https://pharaonic.dev/packages/php/slugify" target="_blank">PHP Slugify</a>.</h5>
<br>

## Documentation

You can find the detailed documentation here in [Laravel Sluggable Documentation](https://pharaonic.dev/packages/laravel/sluggable).

## PHP Attributes

On PHP 8.0, configure slugs on the model class using the repeatable
`Pharaonic\Laravel\Sluggable\Attributes\Slug` attribute. Keep using the existing
`Pharaonic\Laravel\Sluggable\Sluggable` trait to register Eloquent events:

```php
use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Sluggable\Sluggable;
use Pharaonic\Laravel\Sluggable\Attributes\Slug;

#[Slug(source: 'title')]
class Post extends Model
{
    use Sluggable;
}
```

The default target is `slug`; it can also be explicit:

```php
#[Slug(source: 'title', target: 'slug')]
```

Repeat the attribute for any number of independent slug columns:

```php
#[Slug(source: 'title', target: 'slug', unique: true)]
#[Slug(source: 'seo_title', target: 'seo_slug', separator: '-')]
class Post extends Model
{
    use Sluggable;
}
```

Arguments use the existing option names: `separator`, `unique`, `on_create`,
`on_update`, `include_trashed`, `max_length`, `ascii_only`, and `ascii_lang`.
Omitted or null options inherit package configuration and defaults. Use
`max_length: 0` for no length limit. Callable sources remain available through
`sluggable()` for combining values, relations and computed sources; attribute
sources accept a single column name. The source defaults to `name`.

Configuration priority is `sluggable()` method → PHP Attributes → legacy
`$sluggable` property → package defaults. The selected definitions are never
merged with lower-priority definitions; an empty method result disables slugs.
Defaults fill missing options only; models with no definitions generate no slugs.

Only attributes declared on the model class itself are used. Parent attributes
are not inherited; inherited methods and properties keep their existing behavior.
Empty sources, empty targets and duplicate attribute targets throw `InvalidArgumentException`. Declarations are cached per model class,
while package defaults are resolved on each use.

Existing property and method configuration continues working unchanged. All
configuration styles use the same creating/updating listeners and slug engine.

## Contributing

Thank you for considering contributing to this package! Be one of Pharaonic team.

## License

This package is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
