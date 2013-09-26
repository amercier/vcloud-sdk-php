<?php

namespace VMware\VCloud;

class VAppNetwork extends Resource implements Network
{
    protected $parentNetwork = null;
    protected $gateway = null;
    protected $ipPool = null;

    protected function retrieveParent()
    {
        throw new \Exception('Not implemented');
    }

    public function getName()
    {
        return $this->getModel()->get_networkName();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getVApp()
    {
        return $this->getParent();
    }

    public function getParentNetwork()
    {
        return $this->get('parentNetwork', 'retrieveParentNetwork');
    }

    public function retrieveParentNetwork()
    {
        $parentNetwork = $this->getModel()->getConfiguration()->getParentNetwork();
        return $parentNetwork === null
        ? false
        : $this->getVApp()->getVirtualDataCenter()->getOrganization()->getNetworkById($parentNetwork->get_id());
    }

    public function getFenceMode()
    {
        return $this->getModel()->getConfiguration()->getFenceMode();
    }

    public function getGateway()
    {
        return $this->get('gateway', 'retrieveGateway');
    }

    public function retrieveGateway()
    {
        $scope = $this->getModel()->getConfiguration()->getIpScopes()->getIpScope();
        $scope = $scope[0];
        return new Ip\Address($scope->getGateway());
    }

    public function getIpPool()
    {
        return $this->get('ipPool', 'retrieveIpPool');
    }

    protected function retrieveIpPool()
    {
        $scope = $this->getModel()->getConfiguration()->getIpScopes()->getIpScope();
        $scope = $scope[0];
        $mask = new Ip\Mask($scope->getNetmask());
        $gateway = $this->getGateway();
        $subnet = new Ip\Subnet($mask->apply($gateway), $mask);

        $pool = new Ip\Pool($subnet);

        // Add Ranges
        $ranges = $scope->getIpRanges();
        if ($ranges) {
            foreach ($ranges->getIpRange() as $range) {
                $pool->addRange(new Ip\Range($range->getStartAddress(), $range->getEndAddress()));
            }
        }

        // Allocate allocated addresses
        $allocatedIpAddresses = $scope->getAllocatedIpAddresses();
        if ($allocatedIpAddresses) {
            foreach ($allocatedIpAddresses->getIpAddress() as $address) {
                if ($pool->contains($gateway)) {
                    $pool->allocate($address);
                }
            }
        }

        return $pool;
    }
}
