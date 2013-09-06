<?php

namespace VMware\VCloud;

class File extends ResourceEntity
{
    public function getSize()
    {
        return $this->getModel()->get_size();
    }
}
