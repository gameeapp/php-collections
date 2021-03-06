<?php

declare(strict_types=1);

namespace Gamee\Collections\Tests\Collection;

require_once __DIR__ . '/../../bootstrap.php';

use Gamee\Collections\Collection\ImmutableObjectCollection;
use Gamee\Collections\Tests\Utilities\AnotherClass;
use Gamee\Collections\Tests\Utilities\ItemClass;
use Tester\Assert;
use Tester\TestCase;

/**
 * @testCase
 */
class ImmutableObjectCollectionTest extends TestCase
{

	public function testEmptyCollection(): void
	{
		Assert::noError(function (): void {
			new class ([]) extends ImmutableObjectCollection
			{
				protected function getItemType(): string
				{
					return ItemClass::class;
				}
			};
		}
		);
	}

	public function testBasicFunctionality(): void
	{
		$item1 = new ItemClass(1);
		$collection = $this->createTestCollection($item1);

		Assert::same(1, $collection->current()->getValue());
		$collection->next();
		Assert::same(2, $collection->current()->getValue());
		$collection->rewind();
		Assert::same(1, $collection->current()->getValue());
		$collection2 = $collection->addItem(new ItemClass(4));

		Assert::notSame($collection, $collection2);
	}


	public function testThrowExceptionWhenGetsItemOfWrongType(): void
	{
		$collection = $this->createEmptyTestCollection();

		Assert::exception(function () use ($collection): void {
		$collection->addItem(new \stdClass());
		}, \InvalidArgumentException::class);
	}


	public function testConstructorEntryThrowExceptionOnWrongType(): void
	{
		Assert::exception(
			function (): void {
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
			\InvalidArgumentException::class
		);
	}


	public function testConstructorValid(): void
	{
		Assert::noError(
			function (): void {
				new class ([
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
			}
		);
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


(new ImmutableObjectCollectionTest())->run();
