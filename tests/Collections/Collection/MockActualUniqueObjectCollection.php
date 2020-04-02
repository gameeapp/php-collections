<?php

declare(strict_types=1);

namespace Gamee\Collections\Tests\Collections\Collection;

use Gamee\Collections\Collection\IUniqueObjectCollection;

interface MockActualUniqueObjectCollection extends IUniqueObjectCollection
{

	public function contains(object $item): bool;
}
