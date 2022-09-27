<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Validator\Tests\Fixtures;

/**
 * This class is a hand written simplified version of PHP native `ArrayObject`
 * class, to show that it behaves differently than the PHP native implementation.
 */
class CustomArrayObject implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private $array;

    public function __construct(array $array = null)
    {
        $this->array = $array ?: [];
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->array);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->array[$offset];
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
        }
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->array);
    }

    public function count(): int
    {
        return \count($this->array);
    }

    public function __serialize()
    {
        return $this->array;
    }

    public function __unserialize($serialized)
    {
        $this->array = $serialized;
    }
}
