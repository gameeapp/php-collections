<?php

declare(strict_types=1);

namespace Gamee\Collections\Tests\Collection\Mixin;

require_once __DIR__ . '/../../../bootstrap.php';

use Gamee\Collections\Collection\Mixin\FilterTrait;
use Gamee\Collections\Collection\UniqueObjectCollection;
use Gamee\Collections\Tests\Utilities\ItemClass;
use Tester\Assert;
use Tester\TestCase;

/**
 * @testCase
 */
class FilterTraitTest extends TestCase
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

			use FilterTrait;

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

		$filtered = $uniqueCollection->filter(static function (ItemClass $item) {
			return $item->getValue() > 2;
		});

		Assert::same(3, count($filtered));
	}
}


(new FilterTraitTest())->run();
