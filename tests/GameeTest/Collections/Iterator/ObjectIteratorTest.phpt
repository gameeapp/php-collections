<?php

declare(strict_types=1);

namespace GameeTest\Collections\Iterator;

require_once __DIR__ . '/../../../bootstrap.php';

use Gamee\Collections\Iterator\ObjectIterator;
use Tester\Assert;
use Tester\TestCase;

/**
 * @testCase
 */
class ObjectIteratorTest extends TestCase
{

	public function testBasicFunctionality()
	{
		$objectIterator = new ObjectIterator([1, 2, 3]);

		Assert::same(1, $objectIterator->current());
		$objectIterator->next();
		Assert::same(2, $objectIterator->current());
		Assert::same(1, $objectIterator->key());

		$objectIterator->next();

		Assert::same(3, $objectIterator->current());
		Assert::true($objectIterator->valid());

		$objectIterator->rewind();
		Assert::same(0, $objectIterator->key());
		Assert::same(1, $objectIterator->current());
	}
}

(new ObjectIteratorTest())->run();
