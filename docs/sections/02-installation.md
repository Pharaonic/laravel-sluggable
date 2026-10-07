## Installation

Install the package with Composer. Laravel discovers the service provider automatically.

### Requirements

- PHP 8.0 or 8.1
- Laravel 8.75 or newer within 8.x
- `pharaonic/php-slugify` 8.0.4+ on PHP 8.0 or 8.1.2+ on PHP 8.1 (installed automatically)

### Composer Installation

```bash title="Terminal" no-line-numbers
composer require pharaonic/laravel-sluggable
```

### Publish Configuration

Publishing the config is optional. Without it, the package uses its defaults (separator `-`, unique slugs, generated on create only).

```bash title="Terminal" no-line-numbers
php artisan vendor:publish --tag=sluggable-config
```

This creates `config/pharaonic/sluggable.php`.

:::info Publish Tags
`--tag=laravel-sluggable`, `--tag=pharaonic` and `--tag=pharaonic-config` publish the same file.
:::

### Add a Slug Column

Add the column with the `sluggable` migration macro:

```php title="database/migrations/2020_01_01_000000_create_posts_table.php"
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->sluggable(); // "slug": string, nullable, unique
    $table->timestamps();
});
```

:::success Installation Complete
You're all set! Add the `Sluggable` trait to a model and create a record to see its slug.
:::
