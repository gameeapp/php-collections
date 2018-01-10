<?php

declare(strict_types=1);

namespace Gamee\Collections\Tests\Collection;

require_once __DIR__ . '/../../../bootstrap.php';

use Gamee\Collections\Collection\ImmutableObjectCollection;
use Gamee\Collections\Tests\Utilities\ItemClass;
use Tester\Assert;
use Tester\TestCase;

/**
 * @testCase
 */
class ImmutableObjectCollectionTest extends TestCase
{

	public function testBasicFunctionality()
	{
		$item1 = new ItemClass(1);
		$collection = $this->createTestCollection($item1);

		Assert::same(1, $collection->current()->getValue());
		$collection->next();
		Assert::same(2, $collection->current()->getValue());
		$item1->setValue(1000000);
		$collection->rewind();
		Assert::same(1, $collection->current()->getValue());
		$collection2 = $collection->addItem(new ItemClass(4));

		Assert::notSame($collection, $collection2);
	}


	public function testThrowExceptionWhenGetsItemOfWrongType()
	{
		$collection = $this->createTestCollection();

		Assert::exception(function () use ($collection): void {
		$collection->addItem(new \stdClass());
		}, \InvalidArgumentException::class);
	}


	/**
	 * @return ImmutableObjectCollection
	 */
	private function createTestCollection(ItemClass $item)
	{
		$inputArray = [$item, new ItemClass(2), new ItemClass(3)];

		$collection = new class($inputArray) extends ImmutableObjectCollection
		{

			public function getItemType(): string
			{
				return ItemClass::class;
			}
		};

		return $collection;
	}
}


(new ImmutableObjectCollectionTest())->run();
