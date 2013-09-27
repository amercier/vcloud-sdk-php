<?php

namespace VMware\VCloud;

class ExternalNetwork extends Entity implements Network
{
    protected $parentNetwork = null;
    protected $gateway = null;
    protected $ipPool = null;

    protected function retrieveParent()
    {
        throw new \Exception('Not implemented');
    }

    protected function getImplementationGetterName()
    {
        return 'getAdminNetwork';
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getService()
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
        : $this->getService()->getExternalNetworkByName($parentNetwork->get_name());
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

    public static function factory(\VMware_VCloud_API_QueryResultNetworkRecordType $record, Object $parent)
    {
        $ref = new \VMware_VCloud_API_ReferenceType();
        $ref->set_href($record->get_href());
        $ref->set_id('urn:vcloud:network:' . IdentifiableResource::getIdFromHref($record->get_href()));
        $ref->set_name($record->get_name());
        $ref->set_type('application/vnd.vmware.vcloud.network+xml');

        $externalNetwork = new self($parent, null, $ref);

        return $externalNetwork;
    }
}
