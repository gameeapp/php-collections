<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

class JsonSerializableClass implements \JsonSerializable
{
    public function __construct(
        private int $value,
    ) {
    }


    public function jsonSerialize(): array
    {
        return [
            'value' => $this->value,
        ];
    }
}
