<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

final class ItemDoesNotExistException extends CollectionException
{
    public function __construct(string|int $key, string $type = 'key')
    {
        parent::__construct(
            \sprintf(
                'Item with %s "%s" does not exist.',
                $type,
                $key,
            ),
        );
    }
}
