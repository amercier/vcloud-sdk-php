<?php

namespace VMware\VCloud;

/**
 * @todo deploy(), ...
 */
class DeployableResourceEntity extends ResourceEntity
{
    public function __construct(
        Object $parent,
        \VMware_VCloud_API_ReferenceType $reference = null,
        \VMware_VCloud_API_ResourceEntityType $model = null,
        \VMware_VCloud_SDK_VApp_Abstract $implementation = null
    ) {
        parent::__construct($parent, $reference, $model, $implementation);
    }
}
