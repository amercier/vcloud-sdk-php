<?php

namespace VMware\VCloud;

class VAppNetwork extends Resource implements Network
{
    protected $parentNetwork = null;

    public function getName()
    {
        return $this->getModel()->get_networkName();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getVApp()
    {
        return $this->getParent();
    }

    public function getParentNetwork()
    {
        return $this->get('parentNetwork', 'retrieveParentNetwork');
    }

    public function retrieveParentNetwork()
    {
        $parentNetwork = $this->getModel()->getConfiguration()->getParentNetwork();
        return $parentNetwork === null
        ? false
        : $this->getVApp()->getVirtualDataCenter()->getOrganization()->getNetworkById($parentNetwork->get_id());
    }

    public function getFenceMode()
    {
        return $this->getModel()->getConfiguration()->getFenceMode();
    }
}
