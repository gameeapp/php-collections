<?php

declare(strict_types=1);

namespace Gamee\Collections\Tests\Collection\Mixin;

require_once __DIR__ . '/../../../bootstrap.php';

use Gamee\Collections\Collection\Mixin\SliceTrait;
use Gamee\Collections\Collection\UniqueObjectCollection;
use Gamee\Collections\Tests\Utilities\ItemClass;
use Tester\Assert;
use Tester\TestCase;

/**
 * @testCase
 */
class SliceTraitTest extends TestCase
{
	public function testFilter(): void
	{
		$items = [
			new ItemClass(1),
			new ItemClass(2),
			new ItemClass(3),
			new ItemClass(4),
			new ItemClass(5),
		];

		$uniqueCollection = new class($items) extends UniqueObjectCollection
		{

			use SliceTrait;

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

		/** @var UniqueObjectCollection $sliced */
		$sliced = $uniqueCollection->slice(1, 2);

		Assert::same(2, count($sliced));
		Assert::true($sliced->exists(2));
		Assert::true($sliced->exists(3));
	}
}


(new SliceTraitTest())->run();
