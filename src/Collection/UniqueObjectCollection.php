<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

/**
 * @template IdentifiableObject of object
 */
abstract class UniqueObjectCollection implements \Countable, \IteratorAggregate
{

	/**
	 * @var array<IdentifiableObject>
	 */
	protected array $data = [];


	/**
	 * @param IdentifiableObject $item
	 * @return string|int
	 */
	abstract protected function getIdentifier(object $item);


	/**
	 * Skips items with duplicate key
	 * @param array<IdentifiableObject> $data
	 */
	public function __construct(array $data)
	{
		$uniqueItems = [];

		foreach ($data as $item) {
			$identifier = $this->getIdentifier($item);

			if (!$this->exists($identifier)) {
				$uniqueItems[$identifier] = $item;
			}
		}

		$this->data = $uniqueItems;
	}


	/**
	 * @return \ArrayIterator<IdentifiableObject>
	 */
	public function getIterator(): \ArrayIterator
	{
		return new \ArrayIterator($this->data);
	}


	/**
	 * @throws \RuntimeException
	 * @return static
	 */
	public function mergeWith(UniqueObjectCollection $collection)
	{
		if (get_class($collection) !== static::class) {
			throw new \RuntimeException('Can not merge collections with different item type');
		}

		return new static(
			array_replace($this->data, $collection->data)
		);
	}


	/**
	 * @return static
	 */
	public function slice(int $offset, ?int $limit = null)
	{
		return new static(
			array_slice(
				$this->data,
				$offset,
				$limit,
				true
			)
		);
	}


	/**
	 * @return static
	 */
	public function filter(callable $callback, int $flag = 0)
	{
		return new static(
			array_filter(
				$this->data,
				$callback,
				$flag
			)
		);
	}


	public function map(callable $callback): array
	{
		return array_map(
			$callback,
			$this->data
		);
	}


	/**
	 * @return array|int[]|string[]
	 */
	public function getScalarIds(): array
	{
		return array_keys($this->data);
	}


	/**
	 * @param IdentifiableObject $item
	 * @return static
	 * @throws DuplicateKeyException
	 */
	public function addItem(object $item)
	{
		$identifier = $this->getIdentifier($item);

		if ($this->exists($identifier)) {
			throw new DuplicateKeyException($identifier);
		}

		$collection = new static([]);
		$collection->data = $this->data;
		$collection->data[$identifier] = $item;

		return $collection;
	}


	/**
	 * @param string|int $key
	 */
	public function exists($key): bool
	{
		return array_key_exists($key, $this->data);
	}


	/**
	 * @param string|int $key
	 *
	 * @return mixed
	 * @throws ItemDoesNotExistException
	 */
	public function get($key)
	{
		if (!$this->exists($key)) {
			throw new ItemDoesNotExistException($key);
		}

		return $this->data[$key];
	}


	/**
	 * @param string|int $key
	 *
	 * @return mixed
	 */
	public function find($key)
	{
		if (!$this->exists($key)) {
			return null;
		}

		return $this->data[$key];
	}


	public function count(): int
	{
		return count($this->data);
	}


	public function isEmpty(): bool
	{
		return $this->data === [];
	}


	/**
	 * @param IdentifiableObject $item
	 */
	public function contains(object $item): bool
	{
		return $this->exists(
			$this->getIdentifier($item)
		);
	}


	protected function getItems(): array
	{
		return $this->data;
	}
}
