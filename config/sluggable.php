<?php

return [
    // Keep the slug Unique
    'unique'    => true,

    // Generate slug on Eloquent Create
    'on_create'  => true,

    // Generate slug on Eloquent Update
    'on_update'  => true,

    // Default slug separator
    'separator' => '-',

    // Generate slugs into ASCII only
    'ascii_only' => false,

    // ASCII language
    'ascii_lang' => env('APP_LOCALE', 'en'),
];
