<?php

namespace VMware\VCloud;

class Map
{
    protected $entries;

    public function __construct($entries = array())
    {
        $this->entries = array();
        foreach ($entries as $entry) {
            $this->add($entry);
        }
    }

    public function add(Entry $entry)
    {
        $this->entries[$entry->getKey()] = $entry;
    }

    public function set($key, $value)
    {
        $this->add(new Entry($key, $value));
    }

    public function get($key)
    {
        return $this->has($key) ? $this->entries[$key]->getValue() : null;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->entries);
    }

    public function entrySet()
    {
        return $this->entries;
    }
}
