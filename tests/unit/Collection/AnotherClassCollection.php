<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

/**
 * @extends UniqueObjectCollection<AnotherClass>
 */
final class AnotherClassCollection extends UniqueObjectCollection
{
    /**
     * @param AnotherClass $item
     */
    protected function getIdentifier(object $item): int
    {
        return $item->getValue();
    }
}
