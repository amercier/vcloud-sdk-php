<?php

namespace VMware\VCloud;

class VAppTemplate extends DeployableResourceEntity
{
    protected $virtualMachines = null;

    protected function retrieveParent()
    {
        throw new \Exception('Not implemented');
    }

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

    public static function factory(\VMware_VCloud_API_QueryResultAdminVAppTemplateRecordType $record, Object $parent)
    {
        $ref = new \VMware_VCloud_API_ReferenceType();
        $ref->set_href($record->get_href());
        $ref->set_id('urn:vcloud:vapptemplate:' . IdentifiableResource::getIdFromHref($record->get_href()));
        $ref->set_name($record->get_name());
        $ref->set_type('application/vnd.vmware.vcloud.vAppTemplate+xml');

        $vAppTemplate = new self($parent, null, $ref);

        return $vAppTemplate;
    }
}
