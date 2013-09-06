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
        if (!$this->getImplementation()->isPartOfCatalogItem()) {
            return null;
        }

        $catalogItemLink = $this->getImplementation()->getCatalogItemLink();
        $catalogItem = $this->getService()->createImplementationFromReference($catalogItemLink);
        $catalogId = IdentifiableResource::getIdFromHref($catalogItem->getCatalogRef()->get_href());

        return $this->getVirtualDataCenter()->getOrganization()->getCatalogById($catalogId);
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
