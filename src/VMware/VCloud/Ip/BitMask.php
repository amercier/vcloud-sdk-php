<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Object as Object;
use \VMware\VCloud\Exception as Exception;

class BitMask extends Object
{
    const FIRST = 0x00000000;
    const LAST = 0xFFFFFFFF;

    protected $value = null;

    public static function getUnsignedValue($value)
    {
        return +sprintf('%u', intval($value));
    }

    public function __construct($address)
    {
        // Check parameter
        if (!is_integer($address) && !is_float($address) && !is_string($address)) {
            throw new Exception\InvalidParameter($address, array('string', 'integer', 'double'));
        }

        // If the $address is a number, consider it as the IP Address number representation
        if (is_integer($address) || is_float($address) || is_string($address) && preg_match('/^-?[0-9]+$/', $address)) {
            $this->setValue($address);
        } else { // Otherwise, parse it as a string
            $value = ip2long($address);
            if ($value === false) {
                throw new Exception\InvalidBitMask($address);
            }
            $this->setValue($value);
        }
    }

    protected function setValue($value)
    {
        return $this->set('value', self::getUnsignedValue($value & self::LAST));
    }

    public function getValue()
    {
        return $this->get('value');
    }

    protected function equals($bitMask)
    {
        return $this->getValue() === $bitMask->getValue();
    }

    public function __toString()
    {
        return  (($this->getValue() >> 24) & 255)
            . '.' . (($this->getValue() >> 16) & 255)
            . '.' . (($this->getValue() >> 8 ) & 255)
            . '.' . (($this->getValue() >> 0 ) & 255);
    }
}
