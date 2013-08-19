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

        $this->assertEquals($address2->getAddress(), -256, "new Address('255.255.255.0')->getAddress() === -256");
        $this->assertEquals($address3->getAddress(), 0, "new Address('0.0.0.0')->getAddress() === 0");
        $this->assertEquals($address4->getAddress(), -1, "new Address('255.255.255.255')->getAddress() === -1");
    }

    public function testToString()
    {
        $address1 = new Address('0.0.0.0');
        $address2 = new Address('255.255.255.0');
        $address3 = new Address('255.255.255.255');
        $address4 = new Address('192.168.0.1');

        $this->assertEquals('' . $address1, '0.0.0.0'        , "new Address('0.0.0.0').toString() === '0.0.0.0'");
        $this->assertEquals('' . $address2, '255.255.255.0'  , "new Address('255.255.255.0').toString() === '255.255.255.0'");
        $this->assertEquals('' . $address3, '255.255.255.255', "new Address('255.255.255.255').toString() === '255.255.255.255'");
        $this->assertEquals('' . $address4, '192.168.0.1'    , "new Address('192.168.0.1').toString() === '192.168.0.1'");
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

        $this->assertEquals($address1a->getNext(), $address1b, "new Address('0.0.0.0')->getNext() === '0.0.0.1'");
        $this->assertEquals($address2a->getNext(), $address2b, "new Address('192.168.10.100')->getNext() === '192.168.10.101'");
        $this->assertEquals($address3a->getNext(), $address3b, "new Address('192.168.0.255')->getNext() === '192.168.1.0'");
        $this->assertEquals($address4a->getNext(), $address4b, "new Address('192.255.255.255')->getNext() === '193.0.0.0'");
        $this->assertEquals($address5a->getNext(), $address5b, "new Address('127.255.255.255')->getNext() === '128.0.0.0'");
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

        $this->assertEquals($address1a->getPrevious(), $address1b, "new Address('255.255.255.255')->getPrevious() === '255.255.255.254'");
        $this->assertEquals($address2a->getPrevious(), $address2b, "new Address('192.168.10.100')->getPrevious() === '192.168.10.99'");
        $this->assertEquals($address3a->getPrevious(), $address3b, "new Address('192.168.1.0')->getPrevious() === '192.168.0.255'");
        $this->assertEquals($address4a->getPrevious(), $address4b, "new Address('193.0.0.0')->getPrevious() === '192.255.255.255'");
        $this->assertEquals($address5a->getPrevious(), $address5b, "new Address('128.0.0.0')->getPrevious() === '127.255.255.255'");
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
