<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Object as Object;
use \VMware\VCloud\Exception as Exception;

class Subnet extends Object
{
    protected $network = null;
    protected $mask = null;

    protected static function getValueAsObject($address)
    {
        return $address instanceof Address ? $address : new Address($address);
    }

    protected static function getMaskAsObject($mask)
    {
        return $mask instanceof Mask ? $mask : new Mask($mask);
    }

    public function __construct($network, $mask)
    {
        $this->set('network', self::getValueAsObject($network));
        $this->set('mask', self::getMaskAsObject($mask));

        // Check the network/mask validity
        if ($this->getMask()->apply($this->getNetwork())->getValue() !== $this->getNetwork()->getValue()) {
            throw new Exception\InvalidSubnet(self::getValueAsObject($network), self::getMaskAsObject($mask));
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
        return self::getValueAsObject($address)->getValue() === $this->getNetwork()->getValue();
    }

    public function isBroadcastAddress($address)
    {
        return self::getValueAsObject($address)->getValue() === $this->getBroadcastAddress()->getValue();
    }

    public function isValidAddress($address)
    {
        $realAddress = self::getValueAsObject($address);
        return $this->getMask()->apply($realAddress)->getValue() === $this->getNetwork()->getValue()
            && !$this->isNetworkAddress($realAddress)
            && !$this->isBroadcastAddress($realAddress);
    }
}
