<?php

namespace VMware\VCloud;

class VirtualMachine extends DeployableResourceEntity
{
    protected $virtualCpu = null;
    protected $virtualMemory = null;

    public function getVApp()
    {
        return $this->get('parent');
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
}
