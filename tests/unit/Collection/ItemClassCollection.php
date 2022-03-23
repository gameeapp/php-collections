<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

/**
 * @extends UniqueObjectCollection<ItemClass>
 */
final class ItemClassCollection extends UniqueObjectCollection
{
    /**
     * @param ItemClass $item
     */
    protected function getIdentifier(object $item): int
    {
        return $item->getValue();
    }
}
