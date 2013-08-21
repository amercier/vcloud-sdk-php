<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Object as Object;
use \VMware\VCloud\Exception as Exception;

class Address extends Object
{
    protected $address;

    protected static $lastAddress = null;
    protected static $firstAddress = null;

    public function __construct($address)
    {
        // Check parameter
        if (!is_integer($address) && !is_float($address) && !is_string($address)) {
            throw new Exception\InvalidParameter($address, array('string', 'integer', 'double'));
        }

        // If the $address is a number, consider it as the IP Address number representation
        if (is_integer($address) || is_float($address) || is_string($address) && preg_match('/^-?[0-9]+$/', $address)) {

            $this->set('address', intval($address));

        } else { // Otherwise, parse it as a string

            $ip = ip2long($address);
            if ($ip === false) {
                throw new Exception\IpOutOfRange('IP address ' . $address . ' is invalid');
            }

            $this->set('address', $ip);
        }
    }

    public function getAddress()
    {
        return $this->get('address');
    }

    public function __toString()
    {
        return  (($this->getAddress() >> 24) & 255)
            . '.' . (($this->getAddress() >> 16) & 255)
            . '.' . (($this->getAddress() >> 8 ) & 255)
            . '.' . (($this->getAddress() >> 0 ) & 255);
    }

    public function getNext()
    {
        if ($this->getAddress() === self::getLastAddress()->getAddress()) { // 255.255.255.255
            throw new Exception\IpOutOfRange('IP address 255.255.255.255 has no next address');
        }

        return new self($this->getAddress() + 1);
    }

    public function getPrevious()
    {
        if ($this->getAddress() === self::getFirstAddress()->getAddress()) { // 0.0.0.0
            throw new Exception\IpOutOfRange('IP address 0.0.0.0 has no previous address');
        }

        return new self($this->getAddress() - 1);
    }

    public static function getFirstAddress()
    {
        if (self::$firstAddress === null) {
            self::$firstAddress = new Address('0.0.0.0');
        }
        return self::$firstAddress;
    }

    public static function getLastAddress()
    {
        if (self::$lastAddress === null) {
            self::$lastAddress = new Address('255.255.255.255');
        }
        return self::$lastAddress;
    }
}
