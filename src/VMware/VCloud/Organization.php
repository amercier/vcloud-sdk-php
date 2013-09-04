<?php

namespace VMware\VCloud;

class Organization extends Entity
{
    protected $networks = null;
    protected $virtualDataCenters = null;

    public function __construct(
        Service $parent,
        \VMware_VCloud_API_OrgType $model = null,
        \VMware_VCloud_API_ReferenceType $reference = null
    ) {
        parent::__construct($parent, $model, $reference);
    }

    protected function getImplementationGetterName()
    {
        return 'getOrg';
    }

    public function getService()
    {
        return $this->get('parent');
    }

    public function getFullName()
    {
        return $this->getModel()->getFullName();
    }

    public function getNetworks()
    {
        return $this->get('networks', 'retrieveNetworks');
    }

    protected function retrieveNetworks()
    {
        $networks = array();
        foreach ($this->getImplementation()->getOrgNetworkRefs() as $orgNetworkRef) {
            array_push($networks, new OrganizationNetwork($this, null, $orgNetworkRef));
        }
        return $networks;
    }

    public function getNetworkById($id, $exceptionIfNotFound = true)
    {
        foreach ($this->getNetworks() as $network) {
            if ($network->getId() === $id) {
                return $network;
            }
        }
        if ($exceptionIfNotFound) {
            throw new Exception\ObjectNotFound('Organization Network', 'id', $network->getId(), 'Organization ' . $this->getName());
        } else {
            return false;
        }
    }

    public function getVirtualDataCenters()
    {
        return $this->get('virtualDataCenters', 'retrieveVirtualDataCenters');
    }

    protected function retrieveVirtualDataCenters()
    {
        $virtualDataCenters = array();
        foreach ($this->getImplementation()->getVdcRefs() as $vdcRef) {
            array_push($virtualDataCenters, new VirtualDataCenter($this, null, $vdcRef));
        }
        return $virtualDataCenters;
    }

    public function getVAppByName($name, $notFoundException = true)
    {
        foreach ($this->getVirtualDataCenters() as $virtualDataCenter) {
            $vApp = $virtualDataCenter->getVAppByName($name, false);
            if ($vApp !== false) {
                return $vApp;
            }
        }
        if ($notFoundException) {
            throw new Exception\ObjectNotFound('vApp', 'name', 'Virtual Datacenter ' . $this->getName());
        } else {
            return false;
        }
    }
}
