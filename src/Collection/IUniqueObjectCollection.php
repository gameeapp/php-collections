<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

interface IUniqueObjectCollection extends \Countable, \Iterator
{

	/**
	 * @param IUniqueObjectCollection $collection
	 * @throws \RuntimeException
	 * @return static
	 */
	public function mergeWith(IUniqueObjectCollection $collection);


	/**
	 * @param int      $offset
	 * @param int|null $limit
	 *
	 * @return static
	 */
	public function slice(int $offset, ?int $limit = null);


	/**
	 * @param callable $callback
	 * @param int $flag
	 *
	 * @return static
	 */
	public function filter(callable $callback, $flag = 0);


	/**
	 * @param ImmutableObjectCollection $immutableObjectCollection
	 *
	 * @return static
	 */
	public static function createFromImmutableObjectCollection(
		ImmutableObjectCollection $immutableObjectCollection
	);


	/**
	 * @param mixed $item
	 *
	 * @return static
	 * @throws DuplicateKeyException
	 */
	public function addItem($item);


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
