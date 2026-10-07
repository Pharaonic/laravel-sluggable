## Blade Directive

The `@slug` directive prints the slug of any value with PHP Slugify's `slug()` helper. It doesn't touch the database.

```blade title="resources/views/posts/index.blade.php"
<a href="/tags/@slug($tag->name)">{{ $tag->name }}</a>
{{-- "Laravel Tips" → /tags/laravel-tips --}}
```

It accepts the same arguments as the helper: value, separator, ASCII only and language.

```blade
@slug($title, '_')
@slug($title, '-', true, 'de')
```

:::warning Escaping
`@slug` echoes its result without `e()`. Slugs contain only letters, numbers and the separator, so this is safe with the default rules, but don't pass a separator that comes from user input.
:::
