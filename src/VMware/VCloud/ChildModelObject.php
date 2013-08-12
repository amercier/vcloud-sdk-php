<?php

namespace VMware\VCloud;

class ChildModelObject extends ModelObject
{
    public function __construct(ModelObject $parent, $modelReferenceOrObject)
    {
        parent::__construct($parent, $modelReferenceOrObject);
    }

    public function getService()
    {
        return $this->getParent()->getService();
    }
}
