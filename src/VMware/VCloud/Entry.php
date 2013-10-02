<?php

namespace VMware\VCloud;

class Entry
{
    protected $key;
    protected $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function keyEquals(Entry $entry)
    {
        return self::keysEquals($entry->getKey());
    }

    protected static function keysEquals($key1, $key2)
    {
        return is_object($key1) && method_exists($key1, 'equals')
            ? $key1->equals($key2)
            : $key1 === $key2;
    }
}
