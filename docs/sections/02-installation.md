## Installation

Install the package with Composer. Laravel discovers the service provider automatically.

### Requirements

- PHP 8.2, 8.3, 8.4 or 8.5
- Laravel 12.x
- `pharaonic/php-slugify` 8.2.1+ on PHP 8.2, 8.3.1+ on PHP 8.3, 8.4.0+ on PHP 8.4 or 8.5.0+ on PHP 8.5 (installed automatically)

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
