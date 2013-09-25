<?php

namespace VMware\VCloud;

abstract class ResourceEntity extends Entity
{
    public function __construct(
        Object $parent,
        \VMware_VCloud_API_EntityType $model = null,
        \VMware_VCloud_API_ReferenceType $reference = null,
        \VMware_VCloud_SDK_Abstract $implementation = null
    ) {
        parent::__construct($parent, $model, $reference, $implementation);
    }

    public function getStatus()
    {
        return new Status($this->getModel()->get_status());
    }
}
