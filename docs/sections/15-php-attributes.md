## PHP Attributes

On PHP 8.0, configure slugs on the model class using the repeatable
`Pharaonic\Sluggable\Attributes\Slug` attribute. Keep using the existing
`Pharaonic\Laravel\Sluggable\Sluggable` trait to register Eloquent events:

```php
use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Sluggable\Sluggable;
use Pharaonic\Sluggable\Attributes\Slug;

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
