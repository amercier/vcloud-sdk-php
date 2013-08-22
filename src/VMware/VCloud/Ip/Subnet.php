<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Object as Object;
use \VMware\VCloud\Exception as Exception;

class Subnet extends Object
{
    protected $network = null;
    protected $mask = null;

    protected static function getAddressAsObject($address)
    {
        return $address instanceof Address ? $address : new Address($address);
    }

    protected static function getMaskAsObject($mask)
    {
        return $mask instanceof Mask ? $mask : new Mask($mask);
    }

    public function __construct($network, $mask)
    {
        $this->set('network', self::getAddressAsObject($network));
        $this->set('mask', self::getMaskAsObject($mask));

        // Check the network/mask validity
        if ($this->getMask()->apply($this->getNetwork())->getAddress() !== $this->getNetwork()->getAddress()) {
            throw new Exception\InvalidSubnet(self::getAddressAsObject($network), self::getMaskAsObject($mask));
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
        return new Address($this->getNetwork()->getAddress() | ~$this->getMask()->getMask());
    }

    public function isNetworkAddress($address)
    {
        return self::getAddressAsObject($address)->getAddress() === $this->getNetwork()->getAddress();
    }

    public function isBroadcastAddress($address)
    {
        return self::getAddressAsObject($address)->getAddress() === $this->getBroadcastAddress()->getAddress();
    }

    public function isValidAddress($address)
    {
        $realAddress = self::getAddressAsObject($address);
        return $this->getMask()->apply($realAddress)->getAddress() === $this->getNetwork()->getAddress()
            && !$this->isNetworkAddress($realAddress)
            && !$this->isBroadcastAddress($realAddress);
    }
}
