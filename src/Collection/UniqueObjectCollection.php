<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

/**
 * @template IdentifiableObject of object
 *
 * @implements \IteratorAggregate<int, IdentifiableObject>
 */
class UniqueObjectCollection implements \Countable, \IteratorAggregate, \JsonSerializable
{
    /** @var array<IdentifiableObject> */
    protected array $data = [];


    /**
     * Skips items with duplicate key
     *
     * @param array<IdentifiableObject> $data
     */
    public function __construct(
        array $data = [],
    ) {
        $this->fill($data);
    }


    /**
     * Skips items with duplicate key
     *
     * @param array<IdentifiableObject> $data
     */
    public function fillEmptyCollection(array $data): void
    {
        if ($this->isNotEmpty()) {
            throw new CollectionIsNotEmptyException;
        }

        $this->fill($data);
    }


    /**
     * Skips items with duplicate key
     *
     * @param array<IdentifiableObject> $data
     */
    private function fill(array $data): void
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
     */
    public function mergeWith(UniqueObjectCollection $collection): static
    {
        if ($collection::class !== static::class) {
            throw new \RuntimeException('Can not merge collections with different item type');
        }

        return new static(
            \array_replace($this->data, $collection->data),
        );
    }


    public function slice(int $offset, ?int $limit = null): static
    {
        return new static(
            \array_slice(
                $this->data,
                $offset,
                $limit,
                true,
            ),
        );
    }


    public function filter(callable $callback, int $flag = 0): static
    {
        return new static(
            \array_filter(
                $this->data,
                $callback,
                $flag,
            ),
        );
    }


    public function map(callable $callback): array
    {
        return \array_map(
            $callback,
            $this->data,
        );
    }


    public function mapWithCustomKey(
        callable $keyCallback,
        callable $valueCallback,
    ): array
    {
        $result = [];

        foreach ($this->data as $item) {
            $result[$keyCallback($item)] = $valueCallback($item);
        }

        return $result;
    }


    public function mapBy(
        callable $keyCallback,
    ): array
    {
        $result = [];

        foreach ($this->data as $item) {
            $result[$keyCallback($item)] = $item;
        }

        return $result;
    }


    public function walk(callable $callback): void
    {
        \array_walk(
            $this->data,
            $callback,
        );
    }


    public function sortBy(callable $callback): void
    {
        \uasort(
            $this->data,
            $callback,
        );
    }


    public function min(callable $callback): mixed
    {
        return \min(
            $this->map($callback),
        );
    }


    public function max(callable $callback): mixed
    {
        return \max(
            $this->map($callback),
        );
    }


    /**
     * @return IdentifiableObject
     */
    public function rand(): object
    {
        return $this->data[\array_rand($this->data, 1)];
    }


    public function randCollection(int $numberOfItems): static
    {
        $keys = \array_rand($this->data, $numberOfItems);

        if (!\is_array($keys)) {
            $keys = [$keys];
        }

        return new static(
            \array_intersect_key(
                $this->data,
                \array_flip($keys),
            ),
        );
    }


    public function all(callable $callback): bool
    {
        return \count($this->filter($callback)) === \count($this->data);
    }


    public function any(callable $callback): bool
    {
        return \count($this->filter($callback)) > 0;
    }


    public function none(callable $callback): bool
    {
        return \count($this->filter($callback)) === 0;
    }


    /**
     * @return array<int|string>
     */
    public function getScalarIds(): array
    {
        return \array_keys($this->data);
    }


    /**
     * @param IdentifiableObject $item
     *
     * @return static
     *
     * @throws DuplicateKeyException
     */
    public function addItem(object $item): static
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
     * @param IdentifiableObject $item
     *
     * @throws ItemDoesNotExistException
     *
     * @return static
     */
    public function removeItem(object $item): static
    {
        $identifier = $this->getIdentifier($item);

        return $this->removeItemByKey($identifier);
    }


    /**
     * @throws ItemDoesNotExistException
     *
     * @return static
     */
    public function removeItemByKey(string|int $key): static
    {
        if (!$this->exists($key)) {
            throw new ItemDoesNotExistException($key);
        }

        $collection = new static([]);
        $collection->data = $this->data;
        unset($collection->data[$key]);

        return $collection;
    }


    public function exists(string|int $key): bool
    {
        return \array_key_exists($key, $this->data);
    }


    /**
     * @return IdentifiableObject
     *
     * @throws ItemDoesNotExistException
     */
    public function getFirst()
    {
        return $this->get(
            \array_key_first($this->data) ?? throw new ItemDoesNotExistException('first', 'index'),
        );
    }


    /**
     * @return IdentifiableObject
     *
     * @throws ItemDoesNotExistException
     */
    public function getLast()
    {
        return $this->get(
            \array_key_last($this->data) ?? throw new ItemDoesNotExistException('last', 'index'),
        );
    }


    /**
     * Starts with one (1)
     *
     * @return IdentifiableObject
     *
     * @throws ItemDoesNotExistException
     */
    public function getByIndex(int $index)
    {
        $keys = \array_keys($this->data);

        if (!isset($keys[$index-1])) {
            throw new ItemDoesNotExistException($index, 'index');
        }

        return $this->get($keys[$index-1]);
    }


    /**
     * @return IdentifiableObject
     *
     * @throws ItemDoesNotExistException
     */
    public function get(string|int $key)
    {
        if (!$this->exists($key)) {
            throw new ItemDoesNotExistException($key);
        }

        return $this->data[$key];
    }


    /**
     * @return IdentifiableObject|null
     */
    public function find(string|int $key)
    {
        if (!$this->exists($key)) {
            return null;
        }

        return $this->data[$key];
    }


    /**
     * @phpstan-param callable(IdentifiableObject): bool $condition
     *
     * @return IdentifiableObject|null
     */
    public function findFirstByCondition(callable $condition)
    {
        foreach ($this->data as $item) {
            if ($condition($item)) {
                return $item;
            }
        }

        return null;
    }


    public function count(): int
    {
        return \count($this->data);
    }


    public function isEmpty(): bool
    {
        return $this->data === [];
    }


    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }


    /**
     * @param IdentifiableObject $item
     */
    public function contains(object $item): bool
    {
        return $this->exists(
            $this->getIdentifier($item),
        );
    }


    /**
     * @return array<int, IdentifiableObject>
     */
    public function toList(): array
    {
        return \array_values($this->getItems());
    }


    /**
     * @return array<int, IdentifiableObject>
     */
    public function jsonSerialize(): array
    {
        return $this->toList();
    }


    /**
     * @param IdentifiableObject $item
     */
    protected function getIdentifier(object $item): string|int
    {
        return \spl_object_hash($item);
    }


    /**
     * @return array<int|string, IdentifiableObject>
     */
    protected function getItems(): array
    {
        return $this->data;
    }
}
