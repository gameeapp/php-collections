<?php

declare(strict_types=1);

namespace Gamee\Collections\Tests\Collection;

require_once __DIR__ . '/../../bootstrap.php';

use Gamee\Collections\Collection\ImmutableObjectCollection;
use Gamee\Collections\Collection\ItemDoesNotExistException;
use Gamee\Collections\Collection\UniqueObjectCollection;
use Gamee\Collections\Tests\Utilities\AnotherClass;
use Gamee\Collections\Tests\Utilities\DummyUniqueItemCollection;
use Gamee\Collections\Tests\Utilities\ItemClass;
use Tester\Assert;
use Tester\TestCase;

/**
 * @testCase
 */
class UniqueObjectCollectionTest extends TestCase
{

	/**
	 * @dataprovider collectionDataProvider
	 */
	public function testCountable(UniqueObjectCollection $collection, int $expectedItemCount): void
	{
		Assert::type(\Countable::class, $collection);
		Assert::same($expectedItemCount, count($collection));
	}


	/**
	 * @dataprovider collectionDataProvider
	 */
	public function testIsEmpty(UniqueObjectCollection $collection, int $expectedItemCount): void
	{
		Assert::same($expectedItemCount === 0, $collection->isEmpty());
	}


	public function testSkipDuplicateItemsDuringCreation(): void
	{
		$collection = $this->createTestCollection(
			[
				new ItemClass(1),
				new ItemClass(2),
				new ItemClass(3),
				new ItemClass(2),
				new ItemClass(5),
				new ItemClass(1),
			]
		);

		Assert::same(4, count($collection));
	}


	public function testExceptionOnWrongItemTypeDuringCreation(): void
	{
		Assert::exception(function (): void {
			$this->createTestCollection(
				[
					new ItemClass(1),
					new AnotherClass(),
					new ItemClass(3),
				]
			);
		}, \InvalidArgumentException::class);
	}


	public function testCreateFromImmutableObjectCollection(): void
	{
		$items = [
			new ItemClass(1),
			new ItemClass(2),
			new ItemClass(3),
			new ItemClass(4),
			new ItemClass(5),
		];

		$immutableObjectCollection = new class ($items) extends ImmutableObjectCollection
		{
			protected function getItemType(): string
			{
				return ItemClass::class;
			}
		};

		$uniqueCollection = DummyUniqueItemCollection::createFromImmutableObjectCollection($immutableObjectCollection);

		Assert::same(5, count($uniqueCollection));
	}


	public function testIterator(): void
	{
		$items = [];
		$itemCount = 5;

		for ($i=0;$i<$itemCount;$i++) {
			$items[] = new ItemClass($i);
		}

		$collection = $this->createTestCollection($items);

		foreach ($collection as $key => $item) {
			Assert::same($items[$key], $item);
		}
	}


	public function testIteratorInIterator(): void
	{
		$items = [];
		$itemCount = 3;

		for ($i=0;$i<$itemCount;$i++) {
			$items[] = new ItemClass($i);
		}

		$collection = $this->createTestCollection($items);

		$outerIterationItemCount = 0;
		foreach ($collection as $key => $item) {
			foreach ($collection as $innerKey => $innerItem) {
				Assert::same($items[$innerKey], $innerItem);
			}

			$outerIterationItemCount++;
		}

		Assert::same($itemCount, $outerIterationItemCount);
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

		$collection = $this->createTestCollection($items);

		/** @var UniqueObjectCollection $sliced */
		$sliced = $collection->slice(1, 2);

		Assert::same(2, count($sliced));
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

		$collection = $this->createTestCollection($items);

		$filtered = $collection->filter(static function (ItemClass $item) {
			return $item->getValue() > 2;
		});

		Assert::same(3, count($filtered));
	}


	public function testExists(): void
	{
		$id = 1;
		$collection = $this->createTestCollection([new ItemClass($id)]);

		Assert::true($collection->exists($id));
	}


	public function testNotExists(): void
	{
		$id = 1;
		$collection = $this->createTestCollection([new ItemClass($id)]);

		Assert::false($collection->exists(999));
	}


	public function testGet(): void
	{
		$id = 666;
		$item = new ItemClass($id);
		$collection = $this->createTestCollection([$item]);

		Assert::same($item, $collection->get($id));
	}


	public function testGetException(): void
	{
		Assert::exception(function (): void {
			$collection = $this->createTestCollection([]);

			$collection->get(78);
		}, ItemDoesNotExistException::class);
	}


	public function testFind(): void
	{
		$id = 666;
		$item = new ItemClass($id);
		$collection = $this->createTestCollection([$item]);

		Assert::same($item, $collection->find($id));
	}


	public function testFindNotFound(): void
	{
		$collection = $this->createTestCollection([]);

		Assert::same(null, $collection->find(666));
	}


	/**
	 * @dataprovider collectionDataProvider
	 */
	public function testAddItem(UniqueObjectCollection $collection, int $expectedItemCount): void
	{
		$newCollection = $collection->addItem(
			new ItemClass(999)
		);

		Assert::same($expectedItemCount, count($collection));
		Assert::same($expectedItemCount + 1, count($newCollection));
	}


	public function testExceptionOnWrongItemTypeInAddItem(): void
	{
		$collection = $this->createTestCollection();

		Assert::exception(function () use ($collection): void {
			$collection->addItem(new AnotherClass());
		}, \InvalidArgumentException::class);
	}


	protected function collectionDataProvider(): array
	{
		return [
			'Two_item_collection' => [$this->createTestCollection(), 2],
			'Empty_collection' => [$this->createTestCollection([]), 0],
		];
	}

	/**
	 * @return UniqueObjectCollection
	 */
	private function createTestCollection($inputArray = null): UniqueObjectCollection
	{
		$inputArray = $inputArray ?? [new ItemClass(1), new ItemClass(2)];

		return new class($inputArray) extends UniqueObjectCollection
		{

			public function getItemType(): string
			{
				return ItemClass::class;
			}


			/**
			 * @param ItemClass $item
			 *
			 * @return int
			 */
			protected function getIdentifier($item): int
			{
				return $item->getValue();
			}
		};
	}
}


(new UniqueObjectCollectionTest())->run();
