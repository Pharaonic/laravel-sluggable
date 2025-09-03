<?php

return [
    // Keep the slug Unique
    'unique'    => true,

    // Generate slug on Eloquent Create
    'onCreate'  => true,

    // Generate slug on Eloquent Update
    'onUpdate'  => true,

    // Default slug separator
    'separator' => '-',

    // Generate slugs into ASCII only
    'ascii_only' => false,

    // ASCII language
    'ascii_lang' => env('APP_LOCALE', 'en'),
];
