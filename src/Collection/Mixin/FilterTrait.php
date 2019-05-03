<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection\Mixin;

trait FilterTrait
{

	/**
	 * @param callable $callback
	 * @param int $flag
	 *
	 * @return static
	 */
	public function filter(callable $callback, $flag = 0): self
	{
		return new static(
			array_filter(
				$this->getItems(),
				$callback,
				$flag
			)
		);
	}


	abstract protected function getItems(): array;
}
