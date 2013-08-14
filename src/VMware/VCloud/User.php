<?php

namespace VMware\VCloud;

class User extends Entity
{
    public function getFullName() {
        return $this->getModel()->getFullName();
    }
}
