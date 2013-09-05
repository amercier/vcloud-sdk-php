<?php

namespace VMware\VCloud;

class VAppTemplate extends DeployableResourceEntity
{
    protected $virtualMachines = null;

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

}
