<?php

namespace Pharaonic\Laravel\Sluggable;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Grammar;
use Illuminate\Support\Str;
use Pharaonic\Slugify\Slugify;
use Pharaonic\Slugify\SlugOptions;

/**
 * Simple API:   protected $sluggable = 'title';            // title -> slug
 * Advanced API: public function sluggable(): array          // column => source|options
 *
 * When both are defined, sluggable() wins and the two are never merged.
 */
trait Sluggable
{
    /**
     * Add the slug columns to the fillable list (only when the model uses one).
     *
     * @return void
     */
    public function initializeSluggable()
    {
        if (! empty($this->fillable)) {
            $this->fillable = array_values(array_unique(array_merge(
                $this->fillable,
                array_keys($this->getSluggableDefinitions())
            )));
        }
    }

    /**
     * Generate the slugs on creating and updating.
     *
     * @return void
     */
    protected static function bootSluggable()
    {
        static::creating(function (self $model) {
            $model->generateSlugs(false);
        });

        static::updating(function (self $model) {
            $model->generateSlugs(true);
        });
    }

    /**
     * Getting slug with the key.
     *
     * @return string
     */
    public function getSlugWithKeyAttribute()
    {
        $isTranslatable = substr(__CLASS__, -11) == 'Translation';
        $class = substr(__CLASS__, 0, -11);

        if ($isTranslatable && class_exists($class)) {
            $relation = Str::camel(class_basename($class));

            if (method_exists($this, $relation)) {
                return implode('-', [$this->getAttribute(Str::snake($relation).'_id'), $this->getAttribute('slug')]);
            }
        }

        return implode('-', [$this->getKey(), $this->getAttribute('slug')]);
    }

    /**
     * Conditional Where for Slug
     *
     * @param  Builder<static>  $scope
     * @return Builder<static>
     */
    public function scopeWhereSlug(Builder $scope, string $slug)
    {
        return $scope->where('slug', $slug);
    }

    /**
     * Find a model by its slug.
     *
     * @param  array<int, string>  $columns  The columns to select.
     * @return \Illuminate\Database\Eloquent\Model|object|null
     */
    public static function findBySlug(string $slug, array $columns = ['*'])
    {
        return static::query()->where('slug', $slug)->first($columns);
    }

    /**
     * Find a model by its slug or throw an exception.
     *
     * @param  array<int, string>  $columns  The columns to select.
     * @return \Illuminate\Database\Eloquent\Model|object
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function findBySlugOrFail(string $slug, array $columns = ['*'])
    {
        return static::query()->where('slug', $slug)->firstOrFail($columns);
    }

    /**
     * Resolve the slug definitions as [column => options].
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getSluggableDefinitions(): array
    {
        if (method_exists($this, 'sluggable')) {
            $definitions = $this->sluggable();
        } elseif (! empty($this->sluggable)) {
            $definitions = ['slug' => $this->sluggable];
        } else {
            return [];
        }

        $defaults = array_merge([
            'separator' => '-',
            'unique' => true,
            'on_create' => true,
            'on_update' => false,
            'include_trashed' => false,
            'max_length' => 255,
            'ascii_only' => false,
            'ascii_lang' => 'en',
        ], (array) config('pharaonic.sluggable', []));

        foreach ($definitions as $column => $definition) {
            if (! is_array($definition) || ! array_key_exists('source', $definition)) {
                $definition = ['source' => $definition];
            }

            $definitions[$column] = array_merge($defaults, $definition);
        }

        return $definitions;
    }

    /**
     * Generate every configured slug for the current save.
     */
    protected function generateSlugs(bool $updating): void
    {
        foreach ($this->getSluggableDefinitions() as $column => $options) {
            if ($updating) {
                if (! $options['on_update'] || $this->isDirty($column)) {
                    continue;
                }

                if (is_string($options['source']) && ! $this->isDirty($options['source'])) {
                    continue;
                }
            } elseif (! $options['on_create'] || ! in_array($this->getAttribute($column), [null, ''], true)) {
                continue;
            }

            $slug = $this->makeSlug($options);
            $current = (string) $this->getAttribute($column);

            if ($updating && $slug !== null && $this->isSameSlug($current, $slug, $options['separator'])) {
                continue;
            }

            if ($slug !== null && $options['unique']) {
                $slug = $this->makeSlugUnique($column, $slug, $options);
            }

            $this->setAttribute($column, $slug);
        }
    }

    /**
     * Slugify the source value; null when the result is empty.
     *
     * @param  array<string, mixed>  $options
     */
    protected function makeSlug(array $options): ?string
    {
        $source = $options['source'];
        $value = is_string($source) ? $this->getAttribute($source) : $source($this);

        $slug = Slugify::of((string) $value, new SlugOptions(
            $options['separator'],
            true,
            (bool) $options['ascii_only'],
            $options['ascii_lang'],
            true,
            $options['max_length'] ? (int) $options['max_length'] : null
        ))->toString();

        return $slug === '' ? null : $slug;
    }

    /**
     * Append the next free suffix (slug, slug-2, slug-3, ...) using one query per check.
     *
     * @param  array<string, mixed>  $options
     */
    protected function makeSlugUnique(string $column, string $slug, array $options, bool $shortened = false): string
    {
        $separator = $options['separator'];
        $escaped = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $slug.$separator);

        $query = $this->newQuery();

        if ($options['include_trashed']) {
            $query->withoutGlobalScope(SoftDeletingScope::class);
        }

        if ($this->exists) {
            $query->whereKeyNot($this->getKey());
        }

        $taken = $query
            ->where(function (Builder $query) use ($column, $slug, $escaped) {
                $query->where($column, $slug)
                    ->whereRaw($this->slugLikeExpression($column), [$escaped.'%'], 'or');
            })
            ->pluck($column)
            ->all();

        if (! $shortened && ! in_array($slug, $taken, true)) {
            return $slug;
        }

        $pattern = '/^'.preg_quote($slug.$separator, '/').'(\d+)$/u';
        $suffix = 1;

        foreach ($taken as $value) {
            if (preg_match($pattern, (string) $value, $matches)) {
                $suffix = max($suffix, (int) $matches[1]);
            }
        }

        $suffix = $separator.($suffix + 1);
        $maxLength = $options['max_length'] ? (int) $options['max_length'] : null;

        if ($maxLength !== null && mb_strlen($slug.$suffix) > $maxLength) {
            $base = mb_substr($slug, 0, max(0, $maxLength - mb_strlen($suffix)));

            while ($separator !== '' && $base !== '' && Str::endsWith($base, $separator)) {
                $base = mb_substr($base, 0, -mb_strlen($separator));
            }

            // The shortened base has its own suffixes, so look them up again.
            if ($base !== '' && $base !== $slug) {
                return $this->makeSlugUnique($column, $base, $options, true);
            }
        }

        return $slug.$suffix;
    }

    /**
     * The "column like ? escape '!'" clause, with the column wrapped by the query grammar.
     */
    protected function slugLikeExpression(string $column): Expression
    {
        return new class($column) implements Expression
        {
            public function __construct(private string $column) {}

            public function getValue(Grammar $grammar): string
            {
                return $grammar->wrap($this->column)." like ? escape '!'";
            }
        };
    }

    /**
     * Whether the current value is already this slug or one of its unique variants.
     */
    protected function isSameSlug(string $current, string $slug, string $separator): bool
    {
        return $current === $slug
            || preg_match('/^'.preg_quote($slug.$separator, '/').'\d+$/u', $current) === 1;
    }
}
