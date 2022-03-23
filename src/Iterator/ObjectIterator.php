<?php

/**
 * @copyright Copyright (c) 2018 gameeapp.com <hello@gameeapp.com>
 *
 * @author Pavel Janda <pavel@gameeapp.com>
 *
 * @package Gamee
 */

declare(strict_types=1);

namespace Gamee\Collections\Iterator;

/**
 * @deprecated will be removed in 5.0, use UniqueObjectCollection
 */
class ObjectIterator implements \Iterator
{
    public function __construct(protected array $data = [])
    {
    }


    public function rewind(): void
    {
        \reset($this->data);
    }


    public function key(): int|string|null
    {
        return \key($this->data);
    }


    public function next(): void
    {
        \next($this->data);
    }


    public function valid(): bool
    {
        return \key($this->data) !== null;
    }


    public function current(): mixed
    {
        return \current($this->data);
    }
}
