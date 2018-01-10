<?php

/**
 * @copyright   Copyright (c) 2018 gameeapp.com <hello@gameeapp.com>
 * @author      Pavel Janda <pavel@gameeapp.com>
 * @package     Gamee
 */

declare(strict_types=1);

namespace Gamee\Collections\Iterator;

class ObjectIterator implements \Iterator
{

	/**
	 * @var int
	 */
	protected $position = 0;

	/**
	 * @var array|mixed[]
	 */
	protected $data;  


	public function __construct(array $data)
	{
		$this->position = 0;
		$this->data = $data;
	}


	public function rewind(): void
	{
		$this->position = 0;
	}


	public function key(): int
	{
		return $this->position;
	}


	public function next(): void
	{
		++$this->position;
	}


	public function valid(): bool
	{
		return isset($this->data[$this->position]);
	}


	public function current()
	{
		return $this->data[$this->position];
	}
}
