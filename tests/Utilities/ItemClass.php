<?php

declare(strict_types=1);

namespace Gamee\Collections\Tests\Utilities;

class ItemClass
{

	/**
	 * @var int
	 */
	private $value;


	public function __construct(int $value)
	{
		$this->value = $value;
	}


	public function getValue(): int
	{
		return $this->value;
	}


	public function setValue(int $value): void
	{
		$this->value = $value;
	}
}
