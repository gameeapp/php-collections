<?php

declare(strict_types=1);

namespace Gamee\Collections\Tests\Collections\Collection;

use Gamee\Collections\Collection\IUniqueObjectCollection;

interface MockActualUniqueObjectCollection extends IUniqueObjectCollection
{

	/**
	 * @param mixed $item
	 */
	public function contains($item): bool;
}
