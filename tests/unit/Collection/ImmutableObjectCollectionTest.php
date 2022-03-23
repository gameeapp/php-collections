<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

use Codeception\Test\Unit;
use Webmozart\Assert\Assert;

final class ImmutableObjectCollectionTest extends Unit
{
    public function testBasicFunctionality(): void
    {
        $item1 = new ItemClass(1);
        $collection = $this->createTestCollection($item1);

        Assert::same($collection->current()->getValue(), 1);
        $collection->next();
        Assert::same($collection->current()->getValue(), 2);
        $collection->rewind();
        Assert::same($collection->current()->getValue(), 1);

        $collection2 = $collection->addItem(new ItemClass(4));

        Assert::notSame($collection2, $collection);
    }


    public function testThrowExceptionWhenGetsItemOfWrongType(): void
    {
        $collection = $this->createEmptyTestCollection();

        Assert::throws(
            static function () use ($collection): void {
                $collection->addItem(new \stdClass);
            },
            \InvalidArgumentException::class,
        );
    }


    public function testConstructorEntryThrowExceptionOnWrongType(): void
    {
        Assert::throws(
            static function (): void {
                new class ([
                    new ItemClass(5),
                    new AnotherClass(2),
                    new AnotherClass(3),
                ]) extends ImmutableObjectCollection
                {
                    protected function getItemType(): string
                    {
                        return ItemClass::class;
                    }
                };
            },
            \InvalidArgumentException::class,
        );
    }


    public function testConstructorValid(): void
    {
        $collection = new class ([
            new ItemClass(1),
            new ItemClass(2),
            new ItemClass(3),
            new ItemClass(4),
        ]) extends ImmutableObjectCollection
        {
            protected function getItemType(): string
            {
                return ItemClass::class;
            }
        };

        Assert::same($collection->count(), 4);
    }


    private function createTestCollection(ItemClass $item): ImmutableObjectCollection
    {
        $inputArray = [$item, new ItemClass(2), new ItemClass(3)];

        return new class($inputArray) extends ImmutableObjectCollection
        {
            public function getItemType(): string
            {
                return ItemClass::class;
            }
        };
    }


    private function createEmptyTestCollection(): ImmutableObjectCollection
    {
        return new class([]) extends ImmutableObjectCollection
        {
            public function getItemType(): string
            {
                return ItemClass::class;
            }
        };
    }
}
