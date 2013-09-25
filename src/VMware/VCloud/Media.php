<?php

namespace VMware\VCloud;

class Media extends ResourceEntity
{
    protected $size = null;
    protected $files = null;

    protected function retrieveParent()
    {
        throw new \Exception('Not implemented');
    }

    public function getVirtualDataCenter()
    {
        return $this->getParent();
    }

    public function getSize()
    {
        return intval($this->getModel()->get_size());
    }

    public function getFiles()
    {
        return $this->get('files', 'retrieveFiles');
    }

    public function retrieveFiles()
    {
        $files = array();
        $filesElement = $this->getModel()->getFiles();
        if ($filesElement !== null) {
            foreach ($filesElement->getFile() as $file) {
                array_push($files, new File($this, null, $file));
            }
        }
        return $files;
    }

    public function getImageType()
    {
        return $this->getModel()->get_imageType();
    }
}
