<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

interface IUniqueObjectCollection extends \Countable, \IteratorAggregate
{

	public static function createFromImmutableObjectCollection(
		ImmutableObjectCollection $immutableObjectCollection
	): self;


	/**
	 * @param mixed $item
	 *
	 * @return IUniqueObjectCollection
	 * @throws DuplicateKeyException
	 */
	public function addItem($item): self;


	public function getIterator(): \ArrayIterator;


	/**
	 * @param string|int $key
	 *
	 * @return bool
	 */
	public function exists($key): bool;


	/**
	 * @param string|int $key
	 *
	 * @return mixed
	 * @throws ItemDoesNotExistException
	 */
	public function get($key);


	public function isEmpty(): bool;
}
