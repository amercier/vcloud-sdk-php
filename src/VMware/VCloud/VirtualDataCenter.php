<?php

namespace VMware\VCloud;

class VirtualDataCenter extends Entity
{
    protected $vApps = null;

    public function getOrganization()
    {
        return $this->get('parent');
    }

    public function getVApps()
    {
        return $this->get('vApps', 'retrieveVApps');
    }

    protected function retrieveVApps()
    {
        $vApps = array();
        foreach ($this->getImplementation()->getVAppRefs() as $vAppRef) {
            array_push($vApps, new VApp($this, null, $vAppRef));
        }
        return $vApps;
    }

    public function getVAppByName($name, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'vApps',
            'name',
            $name,
            'vApp',
            'Virtual Datacenter ' . $this->getName(),
            $exceptionIfNotFound
        );
    }
}
