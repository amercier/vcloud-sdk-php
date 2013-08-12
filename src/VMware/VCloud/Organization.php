<?php

namespace VMware\VCloud;

class Organization extends ModelObject
{
    protected $networks = null;

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
}
