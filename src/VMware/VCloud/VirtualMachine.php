<?php

namespace VMware\VCloud;

class VirtualMachine extends DeployableResourceEntity
{
    public function getVApp()
    {
        return $this->get('parent');
    }
}
