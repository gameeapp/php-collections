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
	 * @var array|mixed[]
	 */
	protected $items = [];


	public function __construct(array $items)
	{
		$this->items = $items;
	}


	public function rewind(): void
	{
		reset($this->items);
	}


	/**
	 * @return int|string|null
	 */
	public function key()
	{
		return key($this->items);
	}


	public function next(): void
	{
		next($this->items);
	}


	public function valid(): bool
	{
		return key($this->items) !== null;
	}


	/**
	 * @return mixed
	 */
	public function current()
	{
		return current($this->items);
	}
}
