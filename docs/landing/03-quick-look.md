---
view: components.packages.quick-look
title: A quick look
subtitle: One attribute on the model, one macro in the migration, and slugs take care of themselves.
file: app/Models/Post.php
language: php
code: |
  use Illuminate\Database\Eloquent\Model;
  use Pharaonic\Laravel\Sluggable\Sluggable;
  use Pharaonic\Sluggable\Attributes\Slug;

  #[Slug(source: 'title')]
  class Post extends Model
  {
      use Sluggable;

      protected $fillable = ['title'];
  }

  Post::create(['title' => 'Hello World'])->slug;  // "hello-world"
  Post::create(['title' => 'Hello World'])->slug;  // "hello-world-2"
  Post::findBySlug('hello-world');                 // Post
---
