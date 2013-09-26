<?php

namespace VMware\VCloud;

class VApp extends DeployableResourceEntity
{
    protected $virtualMachines = null;
    protected $networks = null;

    protected function retrieveParent()
    {
        throw new \Exception('Not implemented');
    }

    public function getVirtualDataCenter()
    {
        return $this->get('parent');
    }

    public function getOwner()
    {
        return new User(
            $this->getVirtualDataCenter()->getOrganization(),
            null,
            $this->getModel()->getOwner()->getUser()
        );
    }

    public function getVirtualMachines()
    {
        return $this->get('virtualMachines', 'retrieveVirtualMachines');
    }

    public function retrieveVirtualMachines()
    {
        $virtualMachines = array();
        foreach ($this->getModel()->getChildren()->getVm() as $vm) {
            array_push($virtualMachines, new VirtualMachine($this, $vm));
        }
        return $virtualMachines;
    }

    public function getNetworks()
    {
        return $this->get('networks', 'retrieveNetworks');
    }

    protected function getNetworkConfigSection()
    {
        $sections = $this->getModel()->getSection();
        return $sections[3];
    }

    public function retrieveNetworks()
    {
        $networks = array();
        foreach ($this->getNetworkConfigSection()->getNetworkConfig() as $vAppNetwork) {
            array_push($networks, new VAppNetwork($this, $vAppNetwork));
        }

        return $networks;
    }
}
