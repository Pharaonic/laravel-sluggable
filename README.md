
<p align="center"><a href="https://pharaonic.io" target="_blank"><img src="https://raw.githubusercontent.com/Pharaonic/logos/main/sluggable.jpg" width="470"></a></p>

<p align="center">
<a href="https://github.com/Pharaonic/laravel-sluggable" target="_blank"><img src="http://img.shields.io/badge/source-pharaonic/laravel--sluggable-blue.svg?style=flat-square" alt="Source"></a> <a href="https://packagist.org/packages/pharaonic/laravel-sluggable" target="_blank"><img src="https://img.shields.io/packagist/v/pharaonic/laravel-sluggable?style=flat-square" alt="Packagist Version"></a><br>
<a href="https://laravel.com" target="_blank"><img src="https://img.shields.io/badge/Laravel->=6.0-red.svg?style=flat-square" alt="Laravel"></a> <img src="https://img.shields.io/packagist/dt/pharaonic/laravel-sluggable?style=flat-square" alt="Packagist Downloads"> <img src="http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Source">
</p>




<h1 align="center">Laravel Sluggable</h1>

## Install

`Hint` Laravel Sluggable requires the Multibyte String `mbstring` extension from PHP.<br>
`Hint` Mainly depends on [Pharaonic/PHP-Slugify](https://github.com/Pharaonic/php-slugify)<br><br>
Install the latest version using [Composer](https://getcomposer.org/):

```bash
$ composer require pharaonic/laravel-sluggable
```
<br>

## Usage Steps

- [Create a slug column on the table](#CS)
- [Sluggable with the model](#US)
- [How to use](#HTU)
- [Config Manipulation](#CM)
<br><br>


<a name="CS"></a>
### Create a slug column on the table
###### Just add this line to your Migration file.
```php
$table->sluggable();
```
<br>

<a name="US"></a>
### Sluggable with the model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Sluggable\Sluggable;

class Article extends Model
{
    use Sluggable;
	
    /**
     * Sluggable attribute's name
     *
     * @var string
     */
    protected $sluggable = 'title';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', ...];
}

```

<h3 align="center">IT RUNS AUTOMATICALLY DEPENDS ON CONFIG.</h3>
<br>

<a name="HTU"></a>

### How To Use
###### Simply create/update your object.
```php
$article = Article::create(['title' => 'Moamen Eltouny']);
echo $article->slug;
```
<br>

<a name="CM"></a>

### Config Manipulation
```bash
$ php artisan vendor:publish --tag="laravel-sluggable"
```
##### You can find your config file in that path `/config/Pharaonic/sluggable.php`.

```php
<?php
return [
    // Keep the slug Unique
    'unique'    => true,

    // Generate slug on Eloquent Create
    'onCreate'  => true,

    // Generate slug on Eloquent Update
    'onUpdate'  => true
];

```
<br><br>



## License

[MIT license](LICENSE.md)
