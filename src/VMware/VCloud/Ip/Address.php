<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Object as Object;
use \VMware\VCloud\Exception as Exception;

class Address extends Object
{
    protected $address;

    public function __construct($address)
    {
        // Check parameter
        if (gettype($address) !== 'string' && gettype($address) !== 'integer') {
            throw new Exception\InvalidParameter($address, array('string', 'integer'));
        }

        // If the $address is a number, consider it as the IP Address number representation
        if (preg_match('/^-?[0-9]+$/', $address)) {

            $this->set('address', intval($address));

            if ($this->getAddress() < -2147483648 || $this->getAddress() > 2147483647) {
                throw new Exception\IpOutOfRange('IP address ' + $this + ' is invalid (' . $address . ')');
            }

        } else { // Otherwise, parse it as a string

            // Retrieve the different part
            $ip = null;
            preg_match('/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/', $address, $ip);
            if (!$ip || $ip[1] > 255 || $ip[2] > 255 || $ip[3] > 255 || $ip[4] > 255) {
                throw new Exception\IpOutOfRange('IP address ' + $address + ' is invalid');
            }

            // Calculate the IP address number
            $this->set('address', (+$ip[1]<<24) + (+$ip[2]<<16) + (+$ip[3]<<8) + (+$ip[4]));
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
        if ($this->getAddress() === 2147483647) { // 0111..111 => 1000..000
            return new self(intval(-2147483648));
        }

        if ($this->getAddress() === -1) { // 255.255.255.255
            throw new Exception\IpOutOfRange('IP address 255.255.255.255 has no next address');
        }

        return new self(intval($this->getAddress() + 1));
    }

    public function getPrevious()
    {
        if ($this->getAddress() === -2147483648) { // 1000..000 => 0111..111
            return new self(intval(2147483647));
        }

        if ($this->getAddress() === 0) { // 0.0.0.0
            throw new Exception\IpOutOfRange('IP address 0.0.0.0 has no previous address');
        }

        return new self(intval($this->getAddress() - 1));
    }
}
