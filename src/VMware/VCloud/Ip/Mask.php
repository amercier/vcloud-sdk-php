<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Object as Object;
use \VMware\VCloud\Exception as Exception;

class Mask extends Object
{
    protected $mask = null;

    public function __construct($mask)
    {
        if (is_numeric($mask)) { // Mask size (Ex: 24)

            // Retrieve the mask size (31 to 0)
            $maskSize = intval($mask);
            if ($maskSize > 32) {
                throw new Exception\InvalidMaskSize($maskSize);
            }

            // Calculate the actual mask (-xxxx)
            // Address::getLastAddress() is the full "ones" number, aka FFFFFFFF in 32-bits
            $this->set('mask', $maskSize === 0 ? 0 : (Address::getLastAddress()->getAddress() << (32 - $maskSize)));

        } else { // Address (Ex: 255.255.255.0)
            $address = $mask instanceof Address ? $mask : new Address($mask);
            $this->set('mask', $address->getAddress());
        }
    }

    public function getMask()
    {
        return $this->get('mask');
    }

    public function getMaskSize()
    {
        switch($this->getMask()) {
            case -1:
                return 32; // Prevents returning Infinity (see Math.log)
            case 0:
                return 0;  // Prevents returning 32
            default:
                return round(32 - log(~($this->getMask()) + 1)/log(2));
        }
    }

    public function apply($address)
    {
        if ($address instanceof Address) {
            return new Address($this->getMask() & $address->getAddress());
        } else {
            $realAddress = new Address($address);
            return new Address($this->getMask() & $realAddress->getAddress());
        }
    }

    public function __toString()
    {
        return '' .  new Address($this->getMask());
    }
}
