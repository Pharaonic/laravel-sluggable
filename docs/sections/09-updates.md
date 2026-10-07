## Updates & Manual Slugs

Slugs end up in links, so they don't change unless you ask for it.

### On Create

A slug is generated only when its column is empty. A value you set yourself is kept:

```php
$post = new Post(['title' => 'Hello World']);
$post->slug = 'custom-url';
$post->save();

$post->slug; // "custom-url"
```

Set `on_create` to `false` in the config to stop generating slugs on create.

### On Update

`on_update` is `false` by default, so changing the source keeps the existing slug:

```php
$post = Post::create(['title' => 'Hello']);
$post->update(['title' => 'Goodbye']);

$post->slug; // "hello"
```

Enable it globally in the config, or per slug:

```php
'slug' => ['source' => 'title', 'on_update' => true],
```

```php
$post->update(['title' => 'Goodbye']);

$post->slug; // "goodbye"
```

With `on_update` enabled:

- A string source is regenerated only when that attribute is dirty. A callable source is evaluated on every update.
- If the new slug is the same as the current one, or one of its suffixed variants (`hello-2` for `hello`), the current slug is kept.
- If you change the slug column yourself in the same save, your value wins.

:::warning Broken Links
Regenerating slugs changes your URLs. If old links are shared or indexed, keep `on_update` disabled or redirect the old slug yourself.
:::
