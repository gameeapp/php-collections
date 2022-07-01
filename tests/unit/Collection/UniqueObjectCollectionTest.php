<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

use Codeception\Test\Unit;
use Webmozart\Assert\Assert;

class UniqueObjectCollectionTest extends Unit
{
    /**
     * @dataProvider collectionDataProvider
     */
    public function testCountable(UniqueObjectCollection $collection, int $expectedItemCount): void
    {
        Assert::isInstanceOf($collection,\Countable::class);
        Assert::same(\count($collection), $expectedItemCount);
    }


    /**
     * @dataProvider collectionDataProvider
     */
    public function testIsEmpty(UniqueObjectCollection $collection, int $expectedItemCount): void
    {
        Assert::same($collection->isEmpty(), $expectedItemCount === 0);
    }


    /**
     * @dataProvider collectionDataProvider
     */
    public function testIsNotEmpty(UniqueObjectCollection $collection, int $expectedItemCount): void
    {
        Assert::same($collection->isNotEmpty(), $expectedItemCount !== 0);
    }


    public function testFillEmptyCollection(): void
    {
        $collection = new ItemClassCollection([]);

        $collection->fillEmptyCollection(
            [
                new ItemClass(3),
                new ItemClass(4),
                new ItemClass(5),
            ],
        );

        Assert::same($collection->count(), 3);
    }


    public function testCollectionIsNotEmptyException(): void
    {
        $collection = new ItemClassCollection(
            [
                new ItemClass(3),
            ],
        );

        Assert::throws(
            static function () use ($collection): void {
                $collection->fillEmptyCollection(
                    [
                        new ItemClass(4),
                    ],
                );
            },
            CollectionIsNotEmptyException::class,
        );
    }


    public function testSkipDuplicateItemsDuringCreation(): void
    {
        $collection = new ItemClassCollection(
            [
                new ItemClass(1),
                new ItemClass(2),
                new ItemClass(3),
                new ItemClass(2),
                new ItemClass(5),
                new ItemClass(1),
            ],
        );

        Assert::same(\count($collection), 4);
    }


    public function testMergeWith(): void
    {
        $items = [
            new ItemClass(1),
            new ItemClass(2),
            new ItemClass(3),
        ];

        $collection1 = new ItemClassCollection($items);

        $items = [
            new ItemClass(3),
            new ItemClass(4),
            new ItemClass(5),
        ];

        $collection2 = new ItemClassCollection($items);

        $mergedCollection = $collection1->mergeWith($collection2);

        Assert::same(\count($mergedCollection), 5);
    }


    public function testExceptionMergeWithDifferentType(): void
    {
        $items = [
            new ItemClass(1),
            new ItemClass(2),
            new ItemClass(3),
        ];

        $collection1 = new ItemClassCollection($items);

        $anotherItems = [
            new AnotherClass(3),
            new AnotherClass(4),
            new AnotherClass(5),
        ];

        $collection2 = new AnotherClassCollection($anotherItems);

        Assert::throws(
            static function () use ($collection1, $collection2): void {
                $collection1->mergeWith($collection2);
            },
            \RuntimeException::class,
        );
    }


    public function testIterator(): void
    {
        $items = [];
        $itemCount = 5;

        for ($i=0;$i<$itemCount;$i++) {
            $items[] = new ItemClass($i);
        }

        $collection = new ItemClassCollection($items);

        foreach ($collection as $key => $item) {
            $a = $item->getValue();
            Assert::integer($a);
            Assert::same($item, $items[$key]);
        }
    }


    public function testIteratorInIterator(): void
    {
        $items = [];
        $itemCount = 3;

        for ($i=0;$i<$itemCount;$i++) {
            $items[] = new ItemClass($i);
        }

        $collection = new ItemClassCollection($items);

        $outerIterationItemCount = 0;

        foreach ($collection as $key => $item) {
            Assert::integer($key);

            foreach ($collection as $innerKey => $innerItem) {
                Assert::same($innerItem, $items[$innerKey]);
            }

            $outerIterationItemCount++;
        }

        Assert::same($outerIterationItemCount, $itemCount);
    }


    public function testSlice(): void
    {
        $items = [
            new ItemClass(1),
            new ItemClass(2),
            new ItemClass(3),
            new ItemClass(4),
            new ItemClass(5),
        ];

        $collection = new ItemClassCollection($items);

        $sliced = $collection->slice(1, 2);

        Assert::same(\count($sliced), 2);
        Assert::true($sliced->exists(2));
        Assert::true($sliced->exists(3));
    }


    public function testFilter(): void
    {
        $items = [
            new ItemClass(1),
            new ItemClass(2),
            new ItemClass(3),
            new ItemClass(4),
            new ItemClass(5),
        ];

        $collection = new ItemClassCollection($items);

        $filtered = $collection->filter(static fn (ItemClass $item) => $item->getValue() > 2);

        Assert::same(\count($filtered), 3);
    }


    public function testMap(): void
    {
        $items = [
            new ItemClass(1),
            new ItemClass(5),
            new ItemClass(30),
        ];

        $collection = new ItemClassCollection($items);

        $actual = $collection->map(
            static fn (ItemClass $itemClass) => $itemClass->getValue() + 1,
        );

        Assert::same(
            $actual,
            [
                1 => 2,
                5 => 6,
                30 => 31,
            ],
        );
    }


    public function testMapWithCustomKey(): void
    {
        $items = [
            new ItemClass(1),
            new ItemClass(5),
            new ItemClass(30),
        ];

        $collection = new ItemClassCollection($items);

        $actual = $collection->mapWithCustomKey(
            static fn (ItemClass $itemClass) => $itemClass->getValue() - 1,
            static fn (ItemClass $itemClass) => $itemClass->getValue() + 1,
        );

        Assert::same(
            $actual,
            [
                0 => 2,
                4 => 6,
                29 => 31,
            ],
        );
    }


    /**
     * @throws ItemDoesNotExistException
     */
    public function testWalk(): void
    {
        $items = [
            new ItemClass(1),
            new ItemClass(5),
            new ItemClass(30),
        ];

        $collection = new ItemClassCollection($items);

        $collection->walk(
            static function (ItemClass $itemClass): void {
                $itemClass->setValue($itemClass->getValue() + 1);
            },
        );

        Assert::same($collection->get(1)->getValue(), 2);
        Assert::same($collection->get(5)->getValue(), 6);
        Assert::same($collection->get(30)->getValue(), 31);
    }


    public function getScalarIds(): void
    {
        $items = [
            new ItemClass(1),
            new ItemClass(5),
            new ItemClass(30),
        ];

        $collection = new ItemClassCollection($items);

        Assert::same($collection->getScalarIds(), [1, 5, 30]);
    }


    /**
     * @dataProvider collectionDataProvider
     *
     * @throws DuplicateKeyException
     */
    public function testAddItem(UniqueObjectCollection $collection, int $expectedItemCount): void
    {
        $newCollection = $collection->addItem(
            new ItemClass(999),
        );

        Assert::same(\count($collection), $expectedItemCount);
        Assert::same(\count($newCollection), $expectedItemCount + 1);
    }


    /**
     * @throws DuplicateKeyException
     */
    public function testAddItemDuplicate(): void
    {
        $collection = new ItemClassCollection([]);

        $newCollection = $collection->addItem(
            new ItemClass(999),
        );

        Assert::throws(
            static function () use ($newCollection): void {
                $newCollection->addItem(
                    new ItemClass(999),
                );
            },
            DuplicateKeyException::class,
        );
    }


    /**
     * @throws ItemDoesNotExistException
     */
    public function testRemoveItem(): void
    {
        $item = new ItemClass(999);

        $collection = new ItemClassCollection(
            [
                $item,
            ],
        );

        Assert::same(\count($collection), 1);

        $newCollection = $collection->removeItem($item);

        Assert::same(\count($newCollection), 0);
    }


    public function testRemoveItemNotExists(): void
    {
        $collection = new ItemClassCollection(
            [
                new ItemClass(7),
            ],
        );

        Assert::same(\count($collection), 1);

        Assert::throws(
            static function () use ($collection): void {
                $collection->removeItem(
                    new ItemClass(999),
                );
            },
            ItemDoesNotExistException::class,
        );
    }


    /**
     * @throws ItemDoesNotExistException
     */
    public function testRemoveItemByKey(): void
    {
        $item = new ItemClass(999);

        $collection = new ItemClassCollection(
            [
                $item,
            ],
        );

        Assert::same(\count($collection), 1);

        $newCollection = $collection->removeItemByKey(999);

        Assert::same(\count($newCollection), 0);
    }


    public function testExists(): void
    {
        $id = 1;
        $collection = new ItemClassCollection([new ItemClass($id)]);

        Assert::true($collection->exists($id));
    }


    public function testNotExists(): void
    {
        $id = 1;
        $collection = new ItemClassCollection([new ItemClass($id)]);

        Assert::false($collection->exists(999));
    }


    public function testGetFirst(): void
    {
        $item = new ItemClass(999);

        $collection = new ItemClassCollection(
            [
                $item,
                new ItemClass(77),
                new ItemClass(1),
            ],
        );

        $first = $collection->getFirst();

        Assert::same(
            $first->getValue(),
            $item->getValue(),
        );
    }


    public function testGetLast(): void
    {
        $item = new ItemClass(999);

        $collection = new ItemClassCollection(
            [
                new ItemClass(1),
                new ItemClass(77),
                $item,
            ],
        );

        $first = $collection->getLast();

        Assert::same(
            $first->getValue(),
            $item->getValue(),
        );
    }


    public function testGetByIndex(): void
    {
        $item = new ItemClass(999);

        $collection = new ItemClassCollection(
            [
                new ItemClass(1),
                $item,
                new ItemClass(77),
            ],
        );

        $first = $collection->getByIndex(2);

        Assert::same(
            $first->getValue(),
            $item->getValue(),
        );
    }


    public function testIndexNotExists(): void
    {
        $collection = new ItemClassCollection(
            [
                new ItemClass(2),
                new ItemClass(4),
                new ItemClass(8),
            ],
        );

        Assert::throws(
            static function () use ($collection): void {
                $collection->getByIndex(4);
            },
            ItemDoesNotExistException::class,
        );
    }


    /**
     * @throws ItemDoesNotExistException
     */
    public function testGet(): void
    {
        $id = 666;
        $item = new ItemClass($id);
        $collection = new ItemClassCollection([$item]);

        Assert::same($collection->get($id), $item);
    }


    public function testGetException(): void
    {
        Assert::throws(
            static function (): void {
                $collection = new ItemClassCollection([]);

                $collection->get(78);
            },
            ItemDoesNotExistException::class,
        );
    }


    public function testFind(): void
    {
        $id = 666;
        $item = new ItemClass($id);
        $collection = new ItemClassCollection([$item]);

        Assert::same($collection->find($id), $item);
    }


    public function testFindNotFound(): void
    {
        $collection = new ItemClassCollection([]);

        Assert::null($collection->find(666));
    }


    public function testFindFirstByCondition(): void
    {
        $collection = new ItemClassCollection(
            [
                new ItemClass(2),
                new ItemClass(4),
                new ItemClass(8),
            ],
        );

        Assert::same(
            $collection->findFirstByCondition(
                static fn (ItemClass $itemClass): bool => $itemClass->getValue() > 3,
            )?->getValue(),
            4,
        );
    }


    public function testContains(): void
    {
        $itemInCollection = new ItemClass(1);
        $itemNotInCollection = new ItemClass(2);

        $collection = new ItemClassCollection([$itemInCollection]);

        Assert::true($collection->contains($itemInCollection));
        Assert::false($collection->contains($itemNotInCollection));
    }


    public function testToList(): void
    {
        $list = [
            new JsonSerializableClass(3),
            new JsonSerializableClass(5),
            new JsonSerializableClass(7),
        ];

        $collection = new UniqueObjectCollection(
            $list,
        );

        $listFromCollection = $collection->toList();

        Assert::same(
            count($listFromCollection),
            count($list),
        );

        Assert::same(
            array_keys($listFromCollection),
            array_keys($list),
        );

        Assert::same(
            $listFromCollection[0],
            $list[0],
        );

        Assert::same(
            $listFromCollection[1],
            $list[1],
        );
    }


    public function testJsonSerialize(): void
    {
        $collection = new UniqueObjectCollection(
            [
                new JsonSerializableClass(3),
                new JsonSerializableClass(5),
                new JsonSerializableClass(7),
            ],
        );

        Assert::same(
            \json_encode($collection, JSON_THROW_ON_ERROR),
            '[{"value":3},{"value":5},{"value":7}]',
        );
    }


    /**
     * @return array<string, array{UniqueObjectCollection, int}>
     */
    protected function collectionDataProvider(): array
    {
        return [
            'Two_item_collection' => [new ItemClassCollection([new ItemClass(1), new ItemClass(2)]), 2],
            'Empty_collection' => [new ItemClassCollection([]), 0],
        ];
    }
}
