<?php

declare(strict_types=1);

namespace Gamee\Collections\Tests\Utilities;

use Gamee\Collections\Collection\UniqueObjectCollection;

/**
 * @extends UniqueObjectCollection<ItemClass>
 */
final class DummyUniqueItemCollection extends UniqueObjectCollection
{

	protected function getItemType(): string
	{
		return ItemClass::class;
	}


	/**
	 * @param ItemClass $item
	 * @return string|int
	 */
	protected function getIdentifier(object $item)
	{
		return $item->getValue();
	}
}
