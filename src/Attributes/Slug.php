<?php

namespace Pharaonic\Laravel\Sluggable\Attributes;

use Attribute;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Slug
{
    public function __construct(
        public string $source = 'name',
        public string $target = 'slug',
        public ?string $separator = null,
        public ?bool $unique = null,
        public ?bool $on_create = null,
        public ?bool $on_update = null,
        public ?bool $include_trashed = null,
        public ?int $max_length = null,
        public ?bool $ascii_only = null,
        public ?string $ascii_lang = null
    ) {
        if (trim($target) === '' || trim($source) === '') {
            throw new InvalidArgumentException('Sluggable source and target must not be empty.');
        }
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $options = get_object_vars($this);
        unset($options['target']);

        return array_filter($options, static fn ($value) => $value !== null);
    }
}
