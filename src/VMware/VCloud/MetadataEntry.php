<?php

namespace VMware\VCloud;

class MetadataEntry extends Resource
{
    protected $entries = array();

    protected function retrieveParent()
    {
        throw new \Exception('Not implemented');
    }

    public function getName()
    {
        return $this->getModel()->getKey();
    }

    public function getValue()
    {
        return $this->getModel()->getTypedValue()->getValue();
    }

    public function getType()
    {
        return preg_replace('/^VMware_VCloud_API_/', '', get_class($this->getModel()->getTypedValue()));
    }

    public function getDomain()
    {
        $domain = $this->getModel()->getDomain();
        return $domain === null ? null : $domain->get_valueOf();
    }

    public function getVisibility()
    {
        $domain = $this->getModel()->getDomain();
        return $domain === null ? null : $domain->get_visibility();
    }

    public function __toString()
    {
        return $this->getName() . ' = ' . $this->getValue();
    }
}
