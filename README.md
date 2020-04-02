[![Latest Stable Version](https://poser.pugx.org/gamee/php-collections/v/stable)](https://packagist.org/packages/gamee/php-collections)
[![License](https://poser.pugx.org/gamee/php-collections/license)](https://packagist.org/packages/gamee/php-collections)
[![Total Downloads](https://poser.pugx.org/gamee/php-collections/downloads)](https://packagist.org/packages/gamee/php-collections)
[![Build Status](https://travis-ci.org/gameeapp/nette-rabbitmq.svg?branch=master)](https://travis-ci.org/gameeapp/nette-rabbitmq)


# php-collections

Useful PHP utilities (Collections, Iterators, etc)

## UniqueObjectCollection usage

```php
use Gamee\Collections\Collection\UniqueObjectCollection;

/**
 * @extends UniqueObjectCollection<UserData>
 */
final class UserDataCollection extends UniqueObjectCollection
{

	protected function getItemType(): string
	{
		return UserData::class;
	}


	/**
	 * @param UserData $item
	 * @return string|int
	 */
	protected function getIdentifier(object $item)
	{
		return $item->getId();
	}
}
```

## ObjectIterator usage

```php

use Gamee\Collections\Iterator\ObjectIterator;

class UserCredentialsDataIterator extends ObjectIterator
{

	public function current(): UserCredentialsData
	{
		return parent::current();
	}
}
```

## ImmutableObjectCollection

```php
use Gamee\Collections\Collection\ImmutableObjectCollection;

final class UserDataCollection extends ImmutableObjectCollection
{

	protected function getItemType(): string
	{
		return UserData::class;
	}


	public function current(): UserData
	{
		return parent::current();
	}
}
```
