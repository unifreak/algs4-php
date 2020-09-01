<?php
namespace Algs;


/**
 * Mock Java Array
 * - fixed size
 * - only int index
 * - restricted types
 */
class Arr extends \SplFixedArray
{
    private $type;
    private const primeTypes = ['string', 'int', 'double', 'float', 'bool'];

    public function __construct($type, $size)
    {
        $this->type = $type;
        parent::__construct($size);
        if ($this->type == 'bool') {
            for ($i = 0; $i < $size; $i++) {
                $this[$i] = false;
            }
        }

        if (! is_string($type)) {
            throw new \InvalidArgumentException("type must be a string");
        }

    }

    public function offsetSet($offset, $value)
    {

        if (in_array($this->type, self::primeTypes)) {
            $checkType = "is_" . $this->type;
            $isExpected = $checkType($value);
        } else {
            $isExpected = $value instanceof $this->type;
        }

        if (! $isExpected) {
            throw new \InvalidArgumentException("expecting a type $this->type");
        }

        parent::offsetSet($offset, $value);
    }
}