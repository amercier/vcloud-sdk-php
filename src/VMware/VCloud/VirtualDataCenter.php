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

    public function getVAppTemplates()
    {
        return $this->get('vAppTemplates', 'retrieveVAppTemplates');
    }

    protected function retrieveVAppTemplates()
    {
        $vAppTemplates = array();
        foreach ($this->getImplementation()->getVAppTemplateRefs() as $vAppTemplateRef) {
            array_push($vAppTemplates, new VAppTemplate($this, null, $vAppTemplateRef));
        }
        return $vAppTemplates;
    }

    public function getVAppTemplateByName($name, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'vAppTemplates',
            'name',
            $name,
            'vAppTemplate',
            'Virtual Datacenter ' . $this->getName(),
            $exceptionIfNotFound
        );
    }
}
