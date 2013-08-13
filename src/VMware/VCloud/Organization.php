<?php

namespace VMware\VCloud;

class Organization extends ModelObject
{
    protected $networks = null;
    protected $virtualDataCenters = null;

    public function __construct(Service $service, $modelReferenceOrObject)
    {
        parent::__construct($service, $modelReferenceOrObject);
    }

    public function getService()
    {
        return $this->get('parent');
    }

    public function getFullName()
    {
        return $this->getModelData()->getFullName();
    }

    public function getNetworks()
    {
        return $this->get('networks', 'createNetworks');
    }

    protected function createNetworks()
    {
        $networks = array();
        foreach ($this->getModel()->getOrgNetworkRefs() as $orgNetworkRef) {
            array_push($networks, new OrganizationNetwork($this, $orgNetworkRef));
        }
        return $networks;
    }

    public function getVirtualDataCenters()
    {
        return $this->get('virtualDataCenters', 'createVirtualDataCenters');
    }

    protected function createVirtualDataCenters()
    {
        $virtualDataCenters = array();
        foreach ($this->getModel()->getVdcRefs() as $vdcRef) {
            array_push($virtualDataCenters, new VirtualDataCenter($this, $vdcRef));
        }
        return $virtualDataCenters;
    }
}
