<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

use Gamee\Collections\Iterator\ObjectIterator;

abstract class UniqueObjectCollection extends ObjectIterator implements IUniqueObjectCollection
{

	/**
	 * Skips items with duplicate key
	 */
	public function __construct(array $items)
	{
		$classItemName = $this->getItemType();
		$uniqueItems = [];

		foreach ($items as $item) {
			$this->assertItemType($item, $classItemName);

			$identifier = $this->getIdentifier($item);
			if (!$this->exists($identifier)) {
				$uniqueItems[$identifier] = $item;
			}
		}

		parent::__construct($uniqueItems);
	}


	/**
	 * @param ImmutableObjectCollection $immutableObjectCollection
	 *
	 * @return static
	 */
	public static function createFromImmutableObjectCollection(
		ImmutableObjectCollection $immutableObjectCollection
	)
	{
		$privateAccessor = \Closure::bind(
			static function (ImmutableObjectCollection $immutableObjectCollection) {
				return $immutableObjectCollection->items;
			},
			null,
			$immutableObjectCollection
		);

		return new static($privateAccessor($immutableObjectCollection));
	}


	protected function getItems(): array
	{
		return $this->items;
	}


	/**
	 * @param int      $offset
	 * @param int|null $limit
	 *
	 * @return static
	 */
	public function slice(int $offset, ?int $limit = null)
	{
		return new static(
			array_slice(
				$this->items,
				$offset,
				$limit,
				true
			)
		);
	}


	/**
	 * @param callable $callback
	 * @param int $flag
	 *
	 * @return static
	 */
	public function filter(callable $callback, $flag = 0)
	{
		return new static(
			array_filter(
				$this->items,
				$callback,
				$flag
			)
		);
	}


	/**
	 * @param mixed $item
	 *
	 * @return static
	 * @throws DuplicateKeyException
	 */
	public function addItem($item)
	{
		$this->assertItemType($item, $this->getItemType());

		$identifier = $this->getIdentifier($item);
		if ($this->exists($identifier)) {
			throw new DuplicateKeyException($identifier);
		}

		$collection = new static([]);
		$collection->items = $this->items;
		$collection->items[$identifier] = $item;

		return $collection;
	}


	/**
	 * @param mixed $item
	 * @param string|int $type
	 */
	private function assertItemType($item, $type): void
	{
		if (!$item instanceof $type) {
			throw new \InvalidArgumentException(static::class . ' only accepts ' . $type);
		}
	}


	/**
	 * @param string|int $key
	 *
	 * @return bool
	 */
	public function exists($key): bool
	{
		return array_key_exists($key, $this->items);
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

		return $this->items[$key];
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

		return $this->items[$key];
	}


	public function count(): int
	{
		return count($this->items);
	}


	public function isEmpty(): bool
	{
		return $this->items === [];
	}


	abstract protected function getItemType(): string;


	/**
	 * @param mixed $item
	 *
	 * @return string|int
	 */
	abstract protected function getIdentifier($item);
}
