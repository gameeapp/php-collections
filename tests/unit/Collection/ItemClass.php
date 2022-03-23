<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

class ItemClass
{
    public function __construct(private int $value)
    {
    }


    public function getValue(): int
    {
        return $this->value;
    }


    public function setValue(int $value): void
    {
        $this->value = $value;
    }
}
