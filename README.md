# php-collections
Useful PHP utilities (Collections, Iterators, etc)

## ObjectIterator usage

```php

use Gamee\Collections\Iterator\ObjectIterator;

class UserCredentialsDTOIterator extends ObjectIterator
{

	public function current(): UserCredentialsDTO
	{
		return parent::current();
	}
}
```
