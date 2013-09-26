<?php

namespace VMware\VCloud;

class User extends Entity
{
    protected function retrieveParent()
    {
        throw new \Exception('Not implemented');
    }

    public function getFullName()
    {
        return $this->getModel()->getFullName();
    }
}
