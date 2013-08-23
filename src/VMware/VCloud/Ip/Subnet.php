<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Object as Object;
use \VMware\VCloud\Exception as Exception;

class Subnet extends Object
{
    protected $network = null;
    protected $mask = null;

    public function __construct($network, $mask)
    {
        $this->set('network', Address::factory($network));
        $this->set('mask', Mask::factory($mask));

        // Check the network/mask validity
        if ($this->getMask()->apply($this->getNetwork())->getValue() !== $this->getNetwork()->getValue()) {
            throw new Exception\InvalidSubnet(Address::factory($network), Mask::factory($mask));
        }
    }

    public function getNetwork()
    {
        return $this->get('network');
    }

    public function getMask()
    {
        return $this->get('mask');
    }

    public function __toString()
    {
        return $this->getNetwork() . '/' . $this->getMask()->getMaskSize();
    }

    public function getBroadcastAddress()
    {
        return new Address($this->getNetwork()->getValue() | ~$this->getMask()->getValue());
    }

    public function isNetworkAddress($address)
    {
        return Address::factory($address)->getValue() === $this->getNetwork()->getValue();
    }

    public function isBroadcastAddress($address)
    {
        return Address::factory($address)->getValue() === $this->getBroadcastAddress()->getValue();
    }

    public function isValidAddress($address)
    {
        $realAddress = Address::factory($address);
        return $this->getMask()->apply($realAddress)->getValue() === $this->getNetwork()->getValue()
            && !$this->isNetworkAddress($realAddress)
            && !$this->isBroadcastAddress($realAddress);
    }
}
