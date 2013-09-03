<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Exception as Exception;

class Mask extends BitMask
{
    public function __construct($mask)
    {
        if (is_numeric($mask)) { // Mask size (Ex: 24)

            // Retrieve the mask size (31 to 0)
            $maskSize = intval($mask);
            if ($maskSize > 32 || $maskSize < 0) {
                throw new Exception\InvalidMaskSize($maskSize);
            }

            // Calculate the actual mask (-xxxx)
            // Address::getLast() is the full "ones" number, aka FFFFFFFF in 32-bits
            parent::__construct(
                $maskSize === 0
                ? Address::getFirst()->getValue()
                : (Address::getLast()->getValue() << (32 - $maskSize))
            );

        } else { // Address (Ex: 255.255.255.0)
            parent::__construct($mask);

            if(!preg_match('/^1*0*$/', decbin($this->getValue()))) {
                throw new Exception\InvalidMask($this);
            }
        }
    }

    public function getMaskSize()
    {
        return substr_count(decbin($this->getValue()), '1');
    }

    public function apply($address)
    {
        if ($address instanceof Address) {
            return new Address($this->getValue() & $address->getValue());
        } else {
            $realAddress = new Address($address);
            return new Address($this->getValue() & $realAddress->getValue());
        }
    }

    public function equals($address)
    {
        return parent::equals(self::factory($address));
    }

    public static function factory($mask)
    {
        return new self($mask instanceof self ? $mask->getMaskSize() : $mask);
    }
}
