<?php

namespace VMware\VCloud;

/**
 * @todo move $reference to IdentifiableResource
 */
class Resource extends Object
{
    protected $parent = null;
    protected $model = null;

    public function __construct(
        Object $parent,
        \VMware_VCloud_API_ResourceType $model = null
    ) {
        $this->set('parent', $parent);
        $this->set('model', $model);
    }

    protected function getParent()
    {
        return $this->get('parent');
    }

    protected function getModel()
    {
        return $this->get('model');
    }

    /**
     * @see Organization
     */
    public function getService()
    {
        return $this->getParent()->getService();
    }
}
