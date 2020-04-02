<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

interface IUniqueObjectCollection extends \Countable, \IteratorAggregate
{

	/**
	 * @return static
	 */
	public static function createFromImmutableObjectCollection(
		ImmutableObjectCollection $immutableObjectCollection
	);


	/**
	 * @throws \RuntimeException
	 * @return static
	 */
	public function mergeWith(UniqueObjectCollection $collection);


	/**
	 * @return static
	 */
	public function slice(int $offset, ?int $limit = null);


	/**
	 * @return static
	 */
	public function filter(callable $callback, int $flag = 0);


	public function map(callable $callback): array;


	/**
	 * @return array|int[]|string[]
	 */
	public function getScalarIds(): array;


	/**
	 * @return static
	 * @throws DuplicateKeyException
	 */
	public function addItem(object $item);


	/**
	 * @param string|int $key
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
