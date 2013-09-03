<?php

namespace VMware\VCloud\Test\Unit;

use VMware\VCloud\Test\ConfigurableTestCase;

class ObjectTest extends ConfigurableTestCase
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

    public function testAddToArray1AtIndex()
    {
        $object = new ObjectChildren();
        $object->addArray1('two');
        $object->addArray1AtIndex('one', 0);

        $this->assertEquals(array('one', 'two'), $object->getArray1());
    }

    /**
     * @expectedException \VMware\VCloud\Exception\IndexOutOfBounds
     */
    public function testAddToArray1AtIndexOutOfBounds()
    {
        $object = new ObjectChildren();
        $object->addArray1AtIndex('two', 1);
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
     * @expectedException \VMware\VCloud\Exception\IndexOutOfBounds
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
