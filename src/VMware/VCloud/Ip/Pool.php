<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Object as Object;

class Pool extends Object
{
    protected $subnet = null;
    protected $ranges = array();
    protected $allocatedAddresses = array();

    public function __construct(Subnet $subnet, $ranges = array(), $allocatedAddresses = array())
    {
        $this->set('subnet', $subnet);
        $this->addRanges($ranges);
        $this->allocateAddresses($allocatedAddresses);
    }

    public function getSubnet()
    {
        return $this->get('subnet');
    }

    public function addRanges($ranges)
    {
        foreach ($ranges as $range) {
            $this->addRange($range);
        }
        return $this;
    }

    public function addRange(Range $range)
    {
        if (!$range->belongsTo($this->getSubnet())) {
            throw new Exception\RangeOutOfSubnet($this->getSubnet(), $range);
        }
        $this->add('ranges', $range);
    }

    public function contains($address)
    {
        if (!$this->getSubnet()->isValidAddress($address)) {
            throw new Exception\IpOutOfSubnet($address, $this->getSubnet());
        }
        foreach ($this->getRanges() as $range) {
            if ($range->contains($range)) {
                return true;
            }
        }
        return false;
    }

    public function allocateAddresses($addresses)
    {
        foreach ($addresses as $address) {
            $this->allocateAddress($address);
        }
        return $this;
    }

    public function allocateAddress(Address $addresses)
    {
        if (!$this->contains($address)) {
            throw new Exception\AddressOutOfPool($this, $address);
        }
        $this->add('allocatedAddresses', $addresses);
    }

    public function isAvailable(Address $address)
    {
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

    public function isAllocated(Address $address)
    {
        return !$this->isAvailable();
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
}
