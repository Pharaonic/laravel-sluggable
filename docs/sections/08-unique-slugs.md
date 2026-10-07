## Unique Slugs

With `unique` enabled (the default), a taken slug gets a numeric suffix:

```php
Post::create(['title' => 'Hello'])->slug; // "hello"
Post::create(['title' => 'Hello'])->slug; // "hello-2"
Post::create(['title' => 'Hello'])->slug; // "hello-3"
```

### How It Works

- Only the slug's own column is checked, so each slug column has its own sequence.
- One query fetches the slug and its `slug-N` variants. The next number is the highest existing suffix plus one, so a gap such as `hello-7` continues with `hello-8`.
- When updating, the current model is excluded, so a model never collides with itself.
- Slugs that only share a prefix are not collisions: `hello-world` doesn't block `hello`.
- `%` and `_` in a slug are escaped in the `LIKE` match.

### Maximum Length

The suffix counts toward `max_length`. When it doesn't fit, the slug is shortened first:

```php
config(['pharaonic.sluggable.max_length' => 10]);

Post::create(['title' => 'abcdefghij'])->slug; // "abcdefghij"
Post::create(['title' => 'abcdefghij'])->slug; // "abcdefgh-2"
```

Set `max_length` to `null` for no limit. Keep it at or below your column length (`255` for `$table->sluggable()`).

### Soft Deletes

For models using `SoftDeletes`, trashed rows are ignored by default, so a new record can reuse a deleted record's slug. Enable `include_trashed` to keep them reserved:

```php
'slug' => ['source' => 'title', 'include_trashed' => true],
```

:::warning Unique Index and Trashed Rows
The unique index created by `$table->sluggable()` also covers trashed rows. If you keep `include_trashed` disabled on a soft-deleting model, saving a slug that a trashed row still holds will fail at the database. Enable `include_trashed` for those models.
:::

### Disabling Uniqueness

Set `unique` to `false` to store the slug as is. Use a plain `$table->string()` column (or `->index()` instead of a unique index) in that case.
