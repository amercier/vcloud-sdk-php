<?php

namespace VMware\VCloud;

abstract class ResourceAllocation extends Object
{
    protected $model;

    public function __construct(\VMware_VCloud_API_OVF_RASD_Type $model)
    {
        $this->set('model', $model);
    }

    protected function getModel()
    {
        return $this->get('model');
    }

    public function getQuantity()
    {
        return intval($this->getModel()->getVirtualQuantity()->get_valueOf());
    }

    public function getQuantityUnits()
    {
        return $this->getModel()->getQuantityUnits()->get_valueOf();
    }

    public function getAllocation()
    {
        return intval($this->getModel()->getVirtualAllocation()->get_valueOf());
    }

    public function getAllocationUnits()
    {
        return $this->getModel()->getAllocationUnits()->get_valueOf();
    }

    public function __toString()
    {
        return $this->getModel()->getElementName()->get_valueOf();
    }
}
