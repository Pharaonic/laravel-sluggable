## Migration Macro

The package registers a `sluggable` macro on the schema `Blueprint`. Its only argument is the target column, `slug` by default.

```php title="database/migrations/2020_01_01_000000_create_posts_table.php"
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');

    $table->sluggable();                // slug
    $table->sluggable('seo_slug');
    $table->sluggable('category_slug');

    $table->timestamps();
});
```

Each call is the same as:

```php
$table->string($column)->nullable()->unique();
```

The migration only owns the column. The source is always configured on the model.

### Adding a Slug to an Existing Table

```php title="database/migrations/2020_01_02_000000_add_slug_to_posts_table.php"
Schema::table('posts', function (Blueprint $table) {
    $table->sluggable();
});
```

Existing rows keep a `null` slug, since slugs are generated when a model is created. Fill them once with a small loop that sets each slug and saves.
