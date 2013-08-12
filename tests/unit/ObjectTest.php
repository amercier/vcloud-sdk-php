<?php

namespace VMware\VCloud\Test\Unit;

require_once dirname(__FILE__) . '/../bootstrap.php';

use VMware\VCloud\Object;

class ObjectChildren extends Object
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

    public function addArray1($value) {
        return $this->add('array1', $value);
    }

    public function removeArray1($value) {
        return $this->remove('array1', $value);
    }

    public function removeArray1ByIndex($index) {
        return $this->removeByIndex('array1', $index);
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

    public function addUnknownField($value) {
        return $this->add('merIlEtFou', $value);
    }

    public function removeUnknownField($value) {
        return $this->remove('merIlEtFou', $value);
    }

    public function removeUnknownFieldByIndex($value) {
        return $this->removeByIndex('merIlEtFou', $value);
    }

    public function addWrongField($value) {
        return $this->add('field1', $value);
    }

    public function removeWrongField($value) {
        return $this->remove('field1', $value);
    }

    public function removeWrongFieldByIndex($value) {
        return $this->removeByIndex('field1', $value);
    }
}


class ObjectTest extends \VCloudTest
{
    public function testGetSetField()
    {
        $object = new ObjectChildren();
        $this->assertEquals(null, $object->getField1());
        $object->setField1('ok');
        $this->assertEquals('ok', $object->getField1());
    }

    public function testGetCreateField()
    {
        $object = new ObjectChildren();
        $this->assertEquals('field2', $object->getField2());
    }

    /**
     * @expectedException \VMware\VCloud\Exception\UnknownClassField
     */
    public function testGetUnknownClassField()
    {
        $object = new ObjectChildren();
        $object->getUnknownField();
    }

    /**
     * @expectedException \VMware\VCloud\Exception\UnknownClassField
     */
    public function testSetUnknownClassField()
    {
        $object = new ObjectChildren();
        $object->setUnknownField('whatever');
    }

    public function testAddToArray1()
    {
        $object = new ObjectChildren();
        $object->addArray1('one');
    }

    public function testRemoveToArray1()
    {
        $object = new ObjectChildren();
        $object->addArray1('one');
        $object->removeArray1('one');
    }

    public function testRemoveByIndexToArray1()
    {
        $object = new ObjectChildren();
        $object->addArray1('one');
        $object->removeArray1ByIndex(0);
    }

    /**
     * @expectedException \VMware\VCloud\Exception\UnknownClassField
     */
    public function testAddUnknownClassField()
    {
        $object = new ObjectChildren();
        $object->addUnknownField('whatever');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\UnknownClassField
     */
    public function testRemoveUnknownClassField()
    {
        $object = new ObjectChildren();
        $object->removeUnknownField('whatever');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\UnknownClassField
     */
    public function testRemoveUnknownClassFieldByIndex()
    {
        $object = new ObjectChildren();
        $object->removeUnknownFieldByIndex(0);
    }

    /**
     * @expectedException \VMware\VCloud\Exception\ClassFieldNotArray
     */
    public function testAddClassFieldNotArray()
    {
        $object = new ObjectChildren();
        $object->addWrongField('whatever');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\ClassFieldNotArray
     */
    public function testRemoveClassFieldNotArray()
    {
        $object = new ObjectChildren();
        $object->removeWrongField('whatever');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\ClassFieldNotArray
     */
    public function testRemoveClassFieldNotArrayByIndex()
    {
        $object = new ObjectChildren();
        $object->removeWrongFieldByIndex(0);
    }

    /**
     * @expectedException \VMware\VCloud\Exception\IndexOutOfRange
     */
    public function testRemoveByIndexToArray1OutOfRange()
    {
        $object = new ObjectChildren();
        $object->addArray1('one');
        $object->removeArray1ByIndex(1);
    }


    /**
     * @expectedException \VMware\VCloud\Exception\ArrayItemNotFound
     */
    public function testRemoveToArray1NotFound()
    {
        $object = new ObjectChildren();
        $object->addArray1('one');
        $object->removeArray1('mer il et fou');
    }

}
