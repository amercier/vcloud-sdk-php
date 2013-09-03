<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Object as Object;
use \VMware\VCloud\Exception as Exception;

class Pool extends Object
{
    protected $subnet = null;
    protected $ranges = array();
    protected $allocatedAddresses = array();
    protected $allowOverlapping = null;

    public function __construct(Subnet $subnet, $ranges = array(), $allocatedAddresses = array(), $allowOverlapping = false)
    {
        $this->set('subnet', $subnet);
        $this->addRanges($ranges);
        $this->allocateAll($allocatedAddresses);
        $this->set('allowOverlapping', $allowOverlapping);
    }

    public function allowsOverlapping()
    {
        return $this->get('allowOverlapping');
    }

    public function getSubnet()
    {
        return $this->get('subnet');
    }

    public function getRanges()
    {
        return $this->get('ranges');
    }

    public function addRange(Range $range)
    {
        if (!$range->belongsTo($this->getSubnet())) {
            throw new Exception\RangeOutOfSubnet($this->getSubnet(), $range);
        }

        // Check overlapping
        if (!$this->allowsOverlapping()) {
            foreach ($this->getRanges() as $existingRange) {
                if ($range->intersects($existingRange)) {
                    throw new Exception\RangeOverlap($range, $existingRange);
                }
            }
        }

        return $this->add('ranges', $range);
    }

    public function addRanges($ranges)
    {
        foreach ($ranges as $range) {
            $this->addRange($range);
        }
        return $this;
    }

    public function contains($address)
    {
        $address = Address::factory($address);

        if (!$this->getSubnet()->isValidAddress($address)) {
            throw new Exception\AddressOutOfSubnet($this->getSubnet(), $address);
        }
        foreach ($this->getRanges() as $range) {
            if ($range->contains($address)) {
                return true;
            }
        }
        return false;
    }

    public function allocate($address)
    {
        $address = Address::factory($address);

        if (!$this->contains($address)) {
            throw new Exception\AddressOutOfPool($this, $address);
        }
        $this->add('allocatedAddresses', $address);
    }

    public function allocateAll($addresses)
    {
        foreach ($addresses as $address) {
            $this->allocate($address);
        }
        return $this;
    }

    public function getAllocatedAddresses()
    {
        return $this->get('allocatedAddresses');
    }

    public function isAllocated($address)
    {
        return !$this->isAvailable($address);
    }

    public function isAvailable($address)
    {
        $address = Address::factory($address);

        if (!$this->contains($address)) {
            throw new Exception\AddressOutOfPool($this, $address);
        }
        foreach ($this->getAllocatedAddresses() as $allocated) {
            if ($address->equals($allocated)) {
                return false;
            }
        }
        return true;
    }

    public function getFirstAvailable()
    {
        foreach ($this->getRanges() as $range) {
            for ($address = $range->getStart(); $range->contains($address); $address = $address->getNext()) {
                if (!$this->isAllocated($address)) {
                    return $address;
                }
            }
        }
        throw new Exception\FullPool($this);
    }

    public function getLastAvailable()
    {
        foreach ($this->getRanges() as $range) {
            for ($address = $range->getEnd(); $range->contains($address); $address = $address->getPrevious()) {
                if (!$this->isAllocated($address)) {
                    return $address;
                }
            }
        }
        throw new Exception\FullPool($this);
    }

    public function __toString()
    {
        return '[ ' . implode(' ; ', $this->getRanges()) . ' ]';
    }
}
