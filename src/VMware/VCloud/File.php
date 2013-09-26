<?php

namespace VMware\VCloud;

class File extends ResourceEntity
{
    protected function retrieveParent()
    {
        throw new \Exception('Not implemented');
    }

    public function getSize()
    {
        return $this->getModel()->get_size();
    }
}
