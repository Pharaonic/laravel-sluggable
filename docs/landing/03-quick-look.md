---
view: components.packages.quick-look
title: A quick look
subtitle: One property on the model, one macro in the migration, and slugs take care of themselves.
file: app/Post.php
language: php
code: |
  use Illuminate\Database\Eloquent\Model;
  use Pharaonic\Laravel\Sluggable\Sluggable;

  class Post extends Model
  {
      use Sluggable;

      protected $fillable = ['title'];

      protected $sluggable = 'title';   // title -> slug
  }

  Post::create(['title' => 'Hello World'])->slug;  // "hello-world"
  Post::create(['title' => 'Hello World'])->slug;  // "hello-world-2"
  Post::findBySlug('hello-world');                 // Post
---
