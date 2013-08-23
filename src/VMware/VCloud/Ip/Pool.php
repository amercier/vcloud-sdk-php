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
        $this->add('range', $range);
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

    public function isAvailable(Address $addresses)
    {

    }

    public function isAllocated(Address $address)
    {

    }

    public function getFirstAvailable()
    {

    }

    public function getLastAvailable()
    {

    }
}
