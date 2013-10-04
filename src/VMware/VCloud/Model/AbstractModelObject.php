<?php

namespace VMware\VCloud\Model;

use VMware\VCloud\Object;
use VMware\VCloud\Service;

class AbstractModelObject extends Object
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->set('service', $service);
    }
}
