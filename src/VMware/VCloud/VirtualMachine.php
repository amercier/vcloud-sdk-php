<?php

namespace VMware\VCloud;

class VirtualMachine extends DeployableResourceEntity
{
    protected $virtualCpu = null;
    protected $virtualMemory = null;
    protected $isVAppTemplate = null;

    protected function retrieveParent()
    {
        return $this->getService()->getVAppById($this->getModel()->getVAppParent()->get_id());
    }

    protected function getImplementationGetterName()
    {
        return 'getVm';
    }

    public function getVApp()
    {
        $parent = $this->get('parent');
        if ($parent instanceof VApp) {
            return $parent;
        } else { // Service
            $service = $parent;
            $implementation = $service->createImplementationFromReference($this->getLinkByRel('up'));
            $vApp = new VApp($service, null, null, $implementation);
            $this->set('parent', $vApp);
            return $vApp;
        }
    }

    public function getVirtualCpu()
    {
        return $this->get('virtualCpu', 'retrieveVirtualCpu');
    }

    protected function retrieveVirtualCpu()
    {
        return new VirtualCpu($this->getImplementation()->getVirtualCpu());
    }

    public function getVirtualMemory()
    {
        return $this->get('virtualMemory', 'retrieveVirtualMemory');
    }

    protected function retrieveVirtualMemory()
    {
        return new VirtualMemory($this->getImplementation()->getVirtualMemory());
    }

    public function isVAppTemplate()
    {
        return $this->get('isVAppTemplate', 'retrieveIsVAppTemplate');
    }

    public function retrieveIsVAppTemplate()
    {
        return $this->getModel()->get_isVAppTemplate() === "1";
    }

    public static function factory(\VMware_VCloud_API_QueryResultAdminVMRecordType $record, Object $parent)
    {
        $ref = new \VMware_VCloud_API_ReferenceType();
        $ref->set_href($record->get_href());
        $ref->set_id('urn:vcloud:vm:' . IdentifiableResource::getIdFromHref($record->get_href()));
        $ref->set_name($record->get_name());
        $ref->set_type('application/vnd.vmware.vcloud.vm+xml');

        $virtualMachine = new self($parent, null, $ref);
        $virtualMachine->set('isVAppTemplate', $record->get_isVAppTemplate() === "1");

        return $virtualMachine;
    }
}
