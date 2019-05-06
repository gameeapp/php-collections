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
	protected $data = [];


	public function __construct(array $data)
	{
		$this->data = $data;
	}


	public function rewind(): void
	{
		reset($this->data);
	}


	/**
	 * @return int|string|null
	 */
	public function key()
	{
		return key($this->data);
	}


	public function next(): void
	{
		next($this->data);
	}


	public function valid(): bool
	{
		return key($this->data) !== null;
	}


	/**
	 * @return mixed
	 */
	public function current()
	{
		return current($this->data);
	}
}
