<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

final class CollectionIsNotEmptyException extends CollectionException
{
	public function __construct()
	{
		parent::__construct(
			'Collection is not empty.'
		);
	}
}
