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
