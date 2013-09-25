<?php

namespace VMware\VCloud;

class Metadata extends Resource
{
    protected $entries;

    protected function retrieveParent()
    {
        throw new \Exception('Not implemented');
    }

    public function getEntries()
    {
        return $this->get('entries', 'retrieveEntries');
    }

    protected function retrieveEntries()
    {
        $entries = array();
        foreach ($this->getModel()->getMetadataEntry() as $e) {
            array_push($entries, new MetadataEntry($this, $e));
        }
        return $entries;
    }

    protected function getEntriesByName($name)
    {
        return array_filter(
            $this->getEntries(),
            function ($entry) {
                return $entry->getName() === $name;
            }
        );
    }
}
