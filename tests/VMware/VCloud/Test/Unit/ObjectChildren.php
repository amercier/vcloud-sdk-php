<?php

namespace VMware\VCloud\Test\Unit;

use VMware\VCloud\Object;

class ObjectChildren extends Object
{
    protected $field1 = null;
    protected $field2 = null;
    protected $array1 = array();
    protected $array2 = null;

    protected function createArray2()
    {
        return array();
    }

    protected function createField2()
    {
        return 'field2';
    }

    public function getField1()
    {
        return $this->get('field1');
    }

    public function setField1($value)
    {
        return $this->set('field1', $value);
    }

    public function getField2()
    {
        return $this->get('field2', 'createField2');
    }

    public function setField2($value)
    {
        return $this->set('field2', $value);
    }

    public function getArray1()
    {
        return $this->get('array1');
    }

    public function addArray1($value)
    {
        return $this->add('array1', $value);
    }

    public function removeArray1($value)
    {
        return $this->remove('array1', $value);
    }

    public function removeArray1ByIndex($index)
    {
        return $this->removeByIndex('array1', $index);
    }

    public function setArray1($value)
    {
        return $this->set('array1', $value);
    }

    public function getArray2()
    {
        return $this->get('array2', 'createField2');
    }

    public function setArray2($value)
    {
        return $this->set('array2', $value);
    }

    public function getUnknownField()
    {
        return $this->get('merIlEtFou');
    }

    public function setUnknownField($value)
    {
        return $this->set('merIlEtFou', $value);
    }

    public function addUnknownField($value)
    {
        return $this->add('merIlEtFou', $value);
    }

    public function removeUnknownField($value)
    {
        return $this->remove('merIlEtFou', $value);
    }

    public function removeUnknownFieldByIndex($value)
    {
        return $this->removeByIndex('merIlEtFou', $value);
    }

    public function addWrongField($value)
    {
        return $this->add('field1', $value);
    }

    public function removeWrongField($value)
    {
        return $this->remove('field1', $value);
    }

    public function removeWrongFieldByIndex($value)
    {
        return $this->removeByIndex('field1', $value);
    }
}
