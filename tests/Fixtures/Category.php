<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class Category extends Model
{
    public $timestamps = false;

    /** @var array<int, string> */
    protected $guarded = [];
}
