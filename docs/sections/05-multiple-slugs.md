## Multiple Slugs

Define a `sluggable()` method when a model needs more than one slug, or a slug in a column other than `slug`. Each array key is the target column and each value is its source.

```php title="app/Models/Product.php"
use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Sluggable\Sluggable;

class Product extends Model
{
    use Sluggable;

    public function sluggable(): array
    {
        return [
            'slug' => 'name',
            'seo_slug' => 'seo_title',
            'share_slug' => 'share_title',
        ];
    }
}
```

Add one column per slug in the migration:

```php title="database/migrations/2020_01_01_000000_create_products_table.php"
$table->sluggable('slug');
$table->sluggable('seo_slug');
$table->sluggable('share_slug');
```

There is no limit on the number of slugs. Each one is generated on its own and checked for uniqueness against its own column only, so `slug` and `seo_slug` may both be `hello` on the same row.

### Method or Property

:::warning Precedence
When both `$sluggable` and `sluggable()` are defined, the `sluggable()` method always takes precedence. The two configurations are never merged.
:::

```php
protected $sluggable = 'title';          // ignored

public function sluggable(): array
{
    return ['seo_slug' => 'seo_title'];   // only seo_slug is generated
}
```

### One Source per Slug

Each slug has exactly one source. To combine several values into one slug, return them from a [callable source](#callable-sources):

```php
'slug' => fn ($model) => $model->brand.' '.$model->name,
```
