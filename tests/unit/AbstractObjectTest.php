<?php

namespace VMware\VCloud\Test\Unit;

require_once dirname(__FILE__) . '/../bootstrap.php';

use VMware\VCloud\AbstractObject;

class AbstractObjectChildren extends AbstractObject
{
    protected $field1 = null;
    protected $field2 = null;
    protected $array1 = array();
    protected $array2 = null;

    protected function createArray2() {
        return array();
    }

    protected function createField2() {
        return 'field2';
    }

    public function getField1() {
        return $this->get('field1');
    }

    public function setField1($value) {
        return $this->set('field1', $value);
    }

    public function getField2() {
        return $this->get('field2', 'createField2');
    }

    public function setField2($value) {
        return $this->set('field2', $value);
    }

    public function getArray1() {
        return $this->get('array1');
    }

    public function setArray1($value) {
        return $this->set('array1', $value);
    }

    public function getArray2() {
        return $this->get('array2', 'createField2');
    }

    public function setArray2($value) {
        return $this->set('array2', $value);
    }

    public function getUnknownField() {
        return $this->get('merIlEtFou');
    }
    public function setUnknownField($value) {
        return $this->set('merIlEtFou', $value);
    }
}


class AbstractObjectTest extends \VCloudTest
{
    public function testGetSetField()
    {
        $object = new AbstractObjectChildren();
        $this->assertEquals(null, $object->getField1());
        $object->setField1('ok');
        $this->assertEquals('ok', $object->getField1());
    }

    public function testGetCreateField()
    {
        $object = new AbstractObjectChildren();
        $this->assertEquals('field2', $object->getField2());
    }

    /**
     * @expectedException \VMware\VCloud\Exception\UnknownClassField
     */
    public function testGetUnknownClassField()
    {
        $object = new AbstractObjectChildren();
        $object->getUnknownField();
    }

    /**
     * @expectedException \VMware\VCloud\Exception\UnknownClassField
     */
    public function testSetUnknownClassField()
    {
        $object = new AbstractObjectChildren();
        $object->setUnknownField('whatever');
    }
}
