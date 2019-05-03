<?php

declare(strict_types=1);

namespace Gamee\Collections\Collection;

final class ItemDoesNotExistException extends \Exception
{

	/**
	 * @param string|int $key
	 */
	public function __construct($key)
	{
		parent::__construct(
			sprintf(
				'Item with key "%s" not exists.',
				$key
			)
		);
	}
}
