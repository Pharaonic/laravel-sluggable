<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Separator
    |--------------------------------------------------------------------------
    |
    | The string used to join the words of a slug and to append the unique
    | suffix (hello-world, hello-world-2, ...).
    |
    */

    'separator' => '-',

    /*
    |--------------------------------------------------------------------------
    | Unique
    |--------------------------------------------------------------------------
    |
    | Keep each slug unique within its own target column by appending a
    | numeric suffix when the slug is already taken.
    |
    */

    'unique' => true,

    /*
    |--------------------------------------------------------------------------
    | Generate On Create / Update
    |--------------------------------------------------------------------------
    |
    | On create, a slug is generated only when its column is empty. On update,
    | an existing slug is regenerated only when "on_update" is enabled and the
    | slug column was not changed manually.
    |
    */

    'on_create' => true,

    'on_update' => false,

    /*
    |--------------------------------------------------------------------------
    | Include Trashed
    |--------------------------------------------------------------------------
    |
    | For models using SoftDeletes, also check soft-deleted records when
    | looking for a unique slug.
    |
    */

    'include_trashed' => false,

    /*
    |--------------------------------------------------------------------------
    | Maximum Length
    |--------------------------------------------------------------------------
    |
    | The maximum slug length in characters, unique suffix included.
    | Use null for no limit.
    |
    */

    'max_length' => 255,

    /*
    |--------------------------------------------------------------------------
    | ASCII
    |--------------------------------------------------------------------------
    |
    | Transliterate slugs to ASCII, and the language used by PHP Slugify for
    | language-specific behavior.
    |
    */

    'ascii_only' => false,

    'ascii_lang' => env('APP_LOCALE', 'en'),
];
