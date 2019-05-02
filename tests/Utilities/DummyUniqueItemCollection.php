<?php

declare(strict_types=1);

namespace Gamee\Collections\Tests\Utilities;

use Gamee\Collections\Collection\UniqueObjectCollection;

final class DummyUniqueItemCollection extends UniqueObjectCollection
{

	protected function getItemType(): string
	{
		return ItemClass::class;
	}


	protected function getIdentifier($item)
	{
		/** @var ItemClass $item */
		return $item->getValue();
	}
}
