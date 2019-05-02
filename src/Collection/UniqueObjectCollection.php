<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

abstract class UniqueObjectCollection implements IUniqueObjectCollection
{

	/**
	 * @var array
	 */
	private $items = [];


	/**
	 * Skips items with duplicate key
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$classItemName = $this->getItemType();

		foreach ($data as $item) {
			$this->assertItemType($item, $classItemName);

			$identifier = $this->getIdentifier($item);
			if (!$this->exists($identifier)) {
				$this->items[$identifier] = $item;
			}
		}
	}


	public static function createFromImmutableObjectCollection(
		ImmutableObjectCollection $immutableObjectCollection
	): IUniqueObjectCollection
	{
		$privateAccessor = \Closure::bind(
			static function (ImmutableObjectCollection $immutableObjectCollection) {
				return $immutableObjectCollection->data;
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
	 * @param mixed $item
	 *
	 * @return IUniqueObjectCollection
	 * @throws DuplicateKeyException
	 */
	public function addItem($item): IUniqueObjectCollection
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


	public function getIterator(): \ArrayIterator
	{
		return new \ArrayIterator($this->items);
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
