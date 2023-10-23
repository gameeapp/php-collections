<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

class ItemClass
{
    public function __construct(
        private int $value,
        private ?int $optional = null,
    )
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


    public function getOptional(): ?int
    {
        return $this->optional;
    }


    public function setOptional(?int $optional): void
    {
        $this->optional = $optional;
    }
}
