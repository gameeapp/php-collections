<?php

/**
 * @copyright Copyright (c) 2018 gameeapp.com <hello@gameeapp.com>
 *
 * @author Pavel Janda <pavel@gameeapp.com>
 *
 * @package Gamee
 */

declare(strict_types=1);

namespace Gamee\Collections\Collection;

use Gamee\Collections\Iterator\ObjectIterator;

/**
 * @deprecated will be removed in 5.0, use UniqueObjectCollection
 */
abstract class ImmutableObjectCollection extends ObjectIterator implements \Countable
{
    final public function __construct(array $data)
    {
        $classItemName = $this->getItemType();

        foreach ($data as $item) {
            if (!$item instanceof $classItemName) {
                throw new \InvalidArgumentException(self::class . ' only accepts ' . $this->getItemType());
            }
        }

        parent::__construct($data);
    }


    abstract protected function getItemType(): string;


    /**
     * @throws \InvalidArgumentException
     */
    public function addItem(mixed $item): self
    {
        $classItemName = $this->getItemType();

        if (!$item instanceof $classItemName) {
            throw new \InvalidArgumentException(self::class . '::addItem() only accepts ' . $this->getItemType());
        }

        return new static(\array_merge($this->data, [$item]));
    }


    public function count(): int
    {
        return \count($this->data);
    }


    public function isEmpty(): bool
    {
        return $this->data === [];
    }
}
