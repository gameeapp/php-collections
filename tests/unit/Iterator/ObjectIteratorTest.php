<?php

declare(strict_types=1);

namespace Gamee\Collections\Iterator;

use Codeception\Test\Unit;
use Webmozart\Assert\Assert;

final class ObjectIteratorTest extends Unit
{
    public function testBasicFunctionality(): void
    {
        $objectIterator = new ObjectIterator([1, 2, 3]);

        Assert::same($objectIterator->current(), 1);
        $objectIterator->next();
        Assert::same($objectIterator->current(), 2);
        Assert::same($objectIterator->key(), 1);

        $objectIterator->next();

        Assert::same($objectIterator->current(), 3);
        Assert::true($objectIterator->valid());

        $objectIterator->rewind();
        Assert::same($objectIterator->key(), 0);
        Assert::same($objectIterator->current(), 1);
    }
}
