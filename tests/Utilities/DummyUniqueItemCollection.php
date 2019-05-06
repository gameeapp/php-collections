<?php

declare(strict_types=1);

namespace Gamee\Collections\Tests\Utilities;

use Gamee\Collections\Collection\UniqueObjectCollection;

final class DummyUniqueItemCollection extends UniqueObjectCollection
{

	public function current(): ItemClass
	{
		return parent::current();
	}


	protected function getItemType(): string
	{
		return ItemClass::class;
	}


	/**
	 * {@inheritDoc}
	 */
	protected function getIdentifier($item)
	{
		/** @var ItemClass $item */
		return $item->getValue();
	}
}
