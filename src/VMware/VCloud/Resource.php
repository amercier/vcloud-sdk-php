<?php

namespace VMware\VCloud;

class Resource extends Object
{
    protected $parent = null;
    protected $reference = null;
    protected $model = null;

    public function __construct(
        Object $parent,
        \VMware_VCloud_API_ReferenceType $reference = null,
        \VMware_VCloud_API_ResourceType $model = null
    ) {
        $this->set('parent', $parent);
        $this->set('reference', $reference);
        $this->set('model' , $model);
    }

    protected function getParent()
    {
        return $this->get('parent');
    }

    protected function getReference()
    {
        return $this->get('reference');
    }

    protected function getModel()
    {
        return $this->get('model');
    }

    protected function getReferenceOrModel()
    {
        return $this->get('reference') === null ? $this->get('model') : $this->get('reference');
    }

    /**
     * @see Organization
     */
    public function getService()
    {
        return $this->getParent()->getService();
    }
}
