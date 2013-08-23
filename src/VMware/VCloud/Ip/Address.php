<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Exception as Exception;

class Address extends BitMask
{
    protected static $LAST_ADDRESS = null;
    protected static $FIRST_ADDRESS = null;

    public function getNext()
    {
        if ($this->getValue() === self::getLast()->getValue()) { // 255.255.255.255
            throw new Exception\IpOutOfRange('IP address 255.255.255.255 has no next address');
        }

        return new self($this->getValue() + 1);
    }

    public function getPrevious()
    {
        if ($this->getValue() === self::getFirst()->getValue()) { // 0.0.0.0
            throw new Exception\IpOutOfRange('IP address 0.0.0.0 has no previous address');
        }

        return new self($this->getValue() - 1);
    }

    public static function getFirst()
    {
        if (self::$FIRST_ADDRESS === null) {
            self::$FIRST_ADDRESS = new Address(BitMask::FIRST);
        }
        return self::$FIRST_ADDRESS;
    }

    public static function getLast()
    {
        if (self::$LAST_ADDRESS === null) {
            self::$LAST_ADDRESS = new Address(BitMask::LAST);
        }
        return self::$LAST_ADDRESS;
    }
}
