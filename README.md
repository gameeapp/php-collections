[![Latest Stable Version](https://poser.pugx.org/gamee/php-collections/v/stable)](https://packagist.org/packages/gamee/php-collections)
[![License](https://poser.pugx.org/gamee/php-collections/license)](https://packagist.org/packages/gamee/php-collections)
[![Total Downloads](https://poser.pugx.org/gamee/php-collections/downloads)](https://packagist.org/packages/gamee/php-collections)
[![Build Status](https://travis-ci.org/gameeapp/nette-rabbitmq.svg?branch=master)](https://travis-ci.org/gameeapp/nette-rabbitmq)


# php-collections
Useful PHP utilities (Collections, Iterators, etc)

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
