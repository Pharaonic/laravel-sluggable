<?php

namespace Pharaonic\Laravel\Sluggable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Sluggable
{
    // /**
    //  * Sluggable Config List
    //  *
    //  * @var array
    //  */
    // public static $sluggableConfig;

    /**
     * Init Sluggable Package
     * Add `slug` field to fillable list.
     *
     * @return void
     */
    public function initializeSluggable()
    {
        if (!$this->sluggable) {
            throw new \Exception('You have to defined the public property `sluggable`.');
        }

        $this->fillable[] = 'slug';
    }

    /**
     * Boot Sluggable Package
     * Create Eloquent Events (Creating, Updating)
     *
     * @return void
     */
    protected static function bootSluggable()
    {
        static::saving(function (Model $model) {
            $isCreating = config('pharaonic.sluggable.on_create') && !$model->getKey() && !$model->isDirty('slug');
            $isUpdating = config('pharaonic.sluggable.on_update') && $model->getKey() && $model->isDirty($model->sluggable);

            if ($isCreating || $isUpdating) {
                $model->slug = $model->generateSlug($model->{$model->sluggable});
            }
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
            $relation = strtolower(class_basename($class));

            if (method_exists($this, $relation)) {
                return implode('-', [$this->{$relation . '_id'}, $this->slug]);
            }
        }

        return implode('-', [$this->getKey(), $this->slug]);
    }

    /**
     * Generate slug for specific record.
     *
     * @param mixed $value
     * @return string|null
     */
    private function generateSlug($value)
    {
        $value = slug(
            $value,
            config('pharaonic.sluggable.separator'),
            config('pharaonic.sluggable.ascii_only'),
            config('pharaonic.sluggable.ascii_lang'),
        );
        
        if (empty($value)) {
            return null;
        }

        if ($this->slug != $value && config('pharaonic.sluggable.unique')) {
            $count = self::where('slug', 'like', $value . '%')->count();
            $value = $count > 0 ? $value . '-' . ++$count : $value;
        }

        return $value;
    }

    /**
     * Conditional Where for Slug
     *
     * @param Builder $scope
     * @param string $slug
     * @return Builder
     */
    public function scopeWhereSlug(Builder $scope, string $slug)
    {
        return $scope->where('slug', $slug);
    }

    /**
     * Find a model by its slug.
     *
     * @param string $slug
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null
     */
    public static function findBySlug(string $slug, array $columns = ['*'])
    {
        return static::whereSlug($slug)->first($columns);
    }

    /**
     * Find a model by its slug or throw an exception.
     *
     * @param string $slug
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function findBySlugOrFail(string $slug, array $columns = ['*'])
    {
        return static::whereSlug($slug)->firstOrFail($columns);
    }
}
