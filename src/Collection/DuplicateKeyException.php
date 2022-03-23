<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

final class DuplicateKeyException extends CollectionException
{
    public function __construct(string|int $key)
    {
        parent::__construct(
            \sprintf(
                'Item with key: "%s" already exists.',
                $key,
            ),
        );
    }
}
