<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Sluggable\Sluggable;

class Article extends Model
{
    use Sluggable;

    protected $fillable = ['title'];

    protected $sluggable = 'title';
}
