<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

interface IUniqueObjectCollection extends \Countable, \IteratorAggregate
{

	/**
	 * @param UniqueObjectCollection $collection
	 * @throws \RuntimeException
	 * @return static
	 */
	public function mergeWith(UniqueObjectCollection $collection);


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


	public function map(callable $callback): array;


	/**
	 * @return array|int[]|string[]
	 */
	public function getScalarIds(): array;


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
