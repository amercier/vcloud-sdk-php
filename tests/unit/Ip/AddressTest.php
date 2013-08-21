<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

use VMware\VCloud\Ip\Address;

class AddressTest extends VCloudTest
{
    public function testConstruct()
    {
        $address1 = new Address('192.168.0.1');
        $address2 = new Address('255.255.255.0');
        $address3 = new Address('0.0.0.0');
        $address4 = new Address('255.255.255.255');

        $this->assertTrue($address1 instanceof Address, "new Address('192.168.0.1') instanceof Address");
        $this->assertTrue($address3 instanceof Address, "new Address('0.0.0.0') instanceof Address");
        $this->assertTrue($address4 instanceof Address, "new Address('255.255.255.255') instanceof Address");
    }

    /**
     * @expectedException \VMware\VCloud\Exception\IpOutOfRange
     */
    public function testConstructIPOutOfRange()
    {
        $address6 = new Address('192.168.256.0');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\InvalidParameter
     */
    public function testConstructInvalidParameter()
    {
        $address6 = new Address(array());
    }

    public function testToString()
    {
        $address1 = new Address('0.0.0.0');
        $address2 = new Address('255.255.255.0');
        $address3 = new Address('255.255.255.255');
        $address4 = new Address('192.168.0.1');

        $this->assertEquals('0.0.0.0'        , '' . $address1, "new Address('0.0.0.0').toString() === '0.0.0.0'");
        $this->assertEquals('255.255.255.0'  , '' . $address2, "new Address('255.255.255.0').toString() === '255.255.255.0'");
        $this->assertEquals('255.255.255.255', '' . $address3, "new Address('255.255.255.255').toString() === '255.255.255.255'");
        $this->assertEquals('192.168.0.1'    , '' . $address4, "new Address('192.168.0.1').toString() === '192.168.0.1'");
    }

    public function testGetNext()
    {
        $address1a = new Address('0.0.0.0');
        $address1b = new Address('0.0.0.1');

        $address2a = new Address('192.168.10.100');
        $address2b = new Address('192.168.10.101');

        $address3a = new Address('192.168.0.255');
        $address3b = new Address('192.168.1.0');

        $address4a = new Address('192.255.255.255');
        $address4b = new Address('193.0.0.0');

        $address5a = new Address('127.255.255.255');
        $address5b = new Address('128.0.0.0');

        $this->assertEquals($address1b, $address1a->getNext(), "new Address('0.0.0.0')->getNext() === '0.0.0.1'");
        $this->assertEquals($address2b, $address2a->getNext(), "new Address('192.168.10.100')->getNext() === '192.168.10.101'");
        $this->assertEquals($address3b, $address3a->getNext(), "new Address('192.168.0.255')->getNext() === '192.168.1.0'");
        $this->assertEquals($address4b, $address4a->getNext(), "new Address('192.255.255.255')->getNext() === '193.0.0.0'");
        $this->assertEquals($address5b, $address5a->getNext(), "new Address('127.255.255.255')->getNext() === '128.0.0.0'");

        $this->assertEquals('' . $address1b, '' . $address1a->getNext(), "new Address('0.0.0.0')->getNext() === '0.0.0.1'");
        $this->assertEquals('' . $address2b, '' . $address2a->getNext(), "new Address('192.168.10.100')->getNext() === '192.168.10.101'");
        $this->assertEquals('' . $address3b, '' . $address3a->getNext(), "new Address('192.168.0.255')->getNext() === '192.168.1.0'");
        $this->assertEquals('' . $address4b, '' . $address4a->getNext(), "new Address('192.255.255.255')->getNext() === '193.0.0.0'");
        $this->assertEquals('' . $address5b, '' . $address5a->getNext(), "new Address('127.255.255.255')->getNext() === '128.0.0.0'");
    }

    /**
     * @expectedException \VMware\VCloud\Exception\IpOutOfRange
     */
    public function testGetNextOnLastAddress()
    {
        $address6 = new Address('255.255.255.255');
        $address6->getNext();
    }

    public function testGetPrevious()
    {
        $address1a = new Address('255.255.255.255');
        $address1b = new Address('255.255.255.254');

        $address2a = new Address('192.168.10.100');
        $address2b = new Address('192.168.10.99');

        $address3a = new Address('192.168.1.0');
        $address3b = new Address('192.168.0.255');

        $address4a = new Address('193.0.0.0');
        $address4b = new Address('192.255.255.255');

        $address5a = new Address('128.0.0.0');
        $address5b = new Address('127.255.255.255');

        $this->assertEquals($address1b, $address1a->getPrevious(), "new Address('255.255.255.255')->getPrevious() === '255.255.255.254'");
        $this->assertEquals($address2b, $address2a->getPrevious(), "new Address('192.168.10.100')->getPrevious() === '192.168.10.99'");
        $this->assertEquals($address3b, $address3a->getPrevious(), "new Address('192.168.1.0')->getPrevious() === '192.168.0.255'");
        $this->assertEquals($address4b, $address4a->getPrevious(), "new Address('193.0.0.0')->getPrevious() === '192.255.255.255'");
        $this->assertEquals($address5b, $address5a->getPrevious(), "new Address('128.0.0.0')->getPrevious() === '127.255.255.255'");

        $this->assertEquals('' . $address1b, '' . $address1a->getPrevious(), "new Address('255.255.255.255')->getPrevious() === '255.255.255.254'");
        $this->assertEquals('' . $address2b, '' . $address2a->getPrevious(), "new Address('192.168.10.100')->getPrevious() === '192.168.10.99'");
        $this->assertEquals('' . $address3b, '' . $address3a->getPrevious(), "new Address('192.168.1.0')->getPrevious() === '192.168.0.255'");
        $this->assertEquals('' . $address4b, '' . $address4a->getPrevious(), "new Address('193.0.0.0')->getPrevious() === '192.255.255.255'");
        $this->assertEquals('' . $address5b, '' . $address5a->getPrevious(), "new Address('128.0.0.0')->getPrevious() === '127.255.255.255'");
    }

    /**
     * @expectedException \VMware\VCloud\Exception\IpOutOfRange
     */
    public function testGetPreviousOnFirstAddress()
    {
        $address6 = new Address('0.0.0.0');
        $address6->getPrevious();
    }


}
