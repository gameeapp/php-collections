<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection\Mixin;

trait SliceTrait
{

	public function slice(int $offset, ?int $limit = null): self
	{
		return new static(
			array_slice(
				$this->getItems(),
				$offset,
				$limit,
				true
			)
		);
	}


	abstract protected function getItems(): array;
}
