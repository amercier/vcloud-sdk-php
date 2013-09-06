<?php

namespace VMware\VCloud;

class VAppTemplate extends DeployableResourceEntity
{
    protected $virtualMachines = null;

    public function getVirtualDataCenter()
    {
        return $this->getParent();
    }

    public function getCatalog()
    {

        die(print_r($this->getService()->createImplementationFromReference($this->getImplementation()->getCatalogItemLink()), true));

        return $this->getImplementation()->isPartOfCatalogItem()
        ? $this->getVirtualDataCenter()->getOrganization()->getCatalogById(
            IdentifiableResource::getIdFromHref($this->getImplementation()->getCatalogItemLink()->get_href())
        )
        : null;
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

}
