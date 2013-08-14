<?php

namespace VMware\VCloud;

class VAppNetwork extends Resource implements Network
{
    public function getName()
    {
        return $this->getModel()->get_networkName();
    }

    public function __toString()
    {
        return $this->getName();
    }
}
