<?php

namespace VMware\VCloud\Test\Unit\Ip;

use VMware\VCloud\Test\ConfigurableTestCase;
use VMware\VCloud\Ip\Address;
use VMware\VCloud\Ip\Mask;
use VMware\VCloud\Ip\Subnet;

class SubnetTest extends ConfigurableTestCase
{
    public function testConstruct()
    {
        $subnet1 = new Subnet('192.168.1.1', 32);
        $subnet2 = new Subnet('192.168.1.0', 24);
        $subnet3 = new Subnet('192.168.0.0', 16);
        $subnet4 = new Subnet('0.0.0.0', 0);

        $this->assertTrue($subnet1 instanceof Subnet, "new Subnet('192.168.1.1', 32) instanceof Subnet");
        $this->assertTrue($subnet2 instanceof Subnet, "new Subnet('192.168.1.0', 24) instanceof Subnet");
        $this->assertTrue($subnet3 instanceof Subnet, "new Subnet('192.168.0.0', 16) instanceof Subnet");
        $this->assertTrue($subnet4 instanceof Subnet, "new Subnet('0.0.0.0', 32) instanceof Subnet");
    }

    public function testGetNetwork()
    {
        $subnet1 = new Subnet('192.168.1.1', 32);
        $subnet2 = new Subnet('192.168.1.0', 24);
        $subnet3 = new Subnet('192.168.0.0', 16);
        $subnet4 = new Subnet('0.0.0.0', 0);

        $this->assertEquals(
            new Address('192.168.1.1'),
            $subnet1->getNetwork(),
            "new Subnet('192.168.1.1', 32)->getNetwork() === new Address('192.168.1.1')"
        );
        $this->assertEquals(
            new Address('192.168.1.0'),
            $subnet2->getNetwork(),
            "new Subnet('192.168.1.0', 24)->getNetwork() === new Address('192.168.1.0')"
        );
        $this->assertEquals(
            new Address('192.168.0.0'),
            $subnet3->getNetwork(),
            "new Subnet('192.168.0.0', 16)->getNetwork() === new Address('192.168.0.0')"
        );
        $this->assertEquals(
            new Address('0.0.0.0'),
            $subnet4->getNetwork(),
            "new Subnet('0.0.0.0', 0)->getNetwork() === new Address('0.0.0.0')"
        );
    }

    public function testGetMask()
    {
        $subnet1 = new Subnet('192.168.1.1', 32);
        $subnet2 = new Subnet('192.168.1.0', 24);
        $subnet3 = new Subnet('192.168.0.0', 16);
        $subnet4 = new Subnet('0.0.0.0', 0);

        $this->assertEquals(
            new Mask('255.255.255.255'),
            $subnet1->getMask(),
            "new Subnet('192.168.1.1', 32)->getMask() === new Mask('255.255.255.255')"
        );
        $this->assertEquals(
            new Mask('255.255.255.0'),
            $subnet2->getMask(),
            "new Subnet('192.168.1.0', 24)->getMask() === new Mask('255.255.255.0')"
        );
        $this->assertEquals(
            new Mask('255.255.0.0'),
            $subnet3->getMask(),
            "new Subnet('192.168.0.0', 16)->getMask() === new Mask('255.255.0.0')"
        );
        $this->assertEquals(
            new Mask('0.0.0.0'),
            $subnet4->getMask(),
            "new Subnet('0.0.0.0', 0)->getMask() === new Mask('0.0.0.0')"
        );
    }

    /**
     * @expectedException \VMware\VCloud\Exception\InvalidSubnet
     */
    public function testConstructInvalidSubnet()
    {
        new Subnet('192.168.1.1', 24);
    }

    public function testToString()
    {
        $subnet1 = new Subnet('192.168.1.1', 32);
        $subnet2 = new Subnet('192.168.1.0', 24);
        $subnet3 = new Subnet('192.168.0.0', 16);
        $subnet4 = new Subnet('0.0.0.0', 0);

        $this->assertEquals(
            '192.168.1.1/32',
            '' . $subnet1,
            "new Subnet('192.168.1.1', 32)->__toString() === '192.168.1.1/32'"
        );

        $this->assertEquals(
            '192.168.1.0/24',
            '' . $subnet2,
            "new Subnet('192.168.1.0', 24)->__toString() === '192.168.1.0/24'"
        );

        $this->assertEquals(
            '192.168.0.0/16',
            '' . $subnet3,
            "new Subnet('192.168.0.0', 16)->__toString() === '192.168.0.0/16'"
        );

        $this->assertEquals(
            '0.0.0.0/0',
            '' . $subnet4,
            "new Subnet('0.0.0.0', 0)->__toString() === '0.0.0.0/0'"
        );
    }

    public function testGetBroadcastAddress()
    {
        $subnet1 = new Subnet('192.168.1.1', 32);
        $subnet2 = new Subnet('192.168.1.0', 24);
        $subnet3 = new Subnet('192.168.0.0', 16);
        $subnet4 = new Subnet('0.0.0.0', 0);

        $this->assertEquals(
            new Address('192.168.1.1'),
            $subnet1->getBroadcastAddress(),
            "new Subnet('192.168.1.1', 32)->getBroadcastAddress() === new Address('192.168.1.1')"
        );
        $this->assertEquals(
            new Address('192.168.1.255'),
            $subnet2->getBroadcastAddress(),
            "new Subnet('192.168.1.0', 24)->getBroadcastAddress() === new Address('192.168.1.255')"
        );
        $this->assertEquals(
            new Address('192.168.255.255'),
            $subnet3->getBroadcastAddress(),
            "new Subnet('192.168.0.0', 16)->getBroadcastAddress() === new Address('192.168.255.255')"
        );
        $this->assertEquals(
            new Address('255.255.255.255'),
            $subnet4->getBroadcastAddress(),
            "new Subnet('0.0.0.0', 0)->getBroadcastAddress() === new Address('255.255.255.255')"
        );
    }

    public function testIsNetworkAddress()
    {
        $subnet1 = new Subnet('192.168.1.1', 32);
        $subnet2 = new Subnet('192.168.1.0', 24);
        $subnet3 = new Subnet('192.168.0.0', 16);
        $subnet4 = new Subnet('0.0.0.0', 0);

        $this->assertTrue($subnet1->isNetworkAddress($subnet1->getNetwork()));
        $this->assertTrue($subnet2->isNetworkAddress($subnet2->getNetwork()));
        $this->assertTrue($subnet3->isNetworkAddress($subnet3->getNetwork()));
        $this->assertTrue($subnet4->isNetworkAddress($subnet4->getNetwork()));

        $this->assertFalse($subnet1->isNetworkAddress($subnet1->getNetwork()->getNext()));
        $this->assertFalse($subnet2->isNetworkAddress($subnet2->getNetwork()->getNext()));
        $this->assertFalse($subnet3->isNetworkAddress($subnet3->getNetwork()->getNext()));
        $this->assertFalse($subnet4->isNetworkAddress($subnet4->getNetwork()->getNext()));

        $this->assertTrue($subnet1->isNetworkAddress($subnet1->getBroadcastAddress()));
        $this->assertFalse($subnet2->isNetworkAddress($subnet2->getBroadcastAddress()));
        $this->assertFalse($subnet3->isNetworkAddress($subnet3->getBroadcastAddress()));
        $this->assertFalse($subnet4->isNetworkAddress($subnet4->getBroadcastAddress()));
    }

    public function testIsBroadcastAddress()
    {
        $subnet1 = new Subnet('192.168.1.1', 32);
        $subnet2 = new Subnet('192.168.1.0', 24);
        $subnet3 = new Subnet('192.168.0.0', 16);
        $subnet4 = new Subnet('0.0.0.0', 0);

        $this->assertTrue($subnet1->isBroadcastAddress($subnet1->getBroadcastAddress()));
        $this->assertTrue($subnet2->isBroadcastAddress($subnet2->getBroadcastAddress()));
        $this->assertTrue($subnet3->isBroadcastAddress($subnet3->getBroadcastAddress()));
        $this->assertTrue($subnet4->isBroadcastAddress($subnet4->getBroadcastAddress()));

        $this->assertFalse($subnet1->isBroadcastAddress($subnet1->getBroadcastAddress()->getPrevious()));
        $this->assertFalse($subnet2->isBroadcastAddress($subnet2->getBroadcastAddress()->getPrevious()));
        $this->assertFalse($subnet3->isBroadcastAddress($subnet3->getBroadcastAddress()->getPrevious()));
        $this->assertFalse($subnet4->isBroadcastAddress($subnet4->getBroadcastAddress()->getPrevious()));

        $this->assertTrue($subnet1->isBroadcastAddress($subnet1->getNetwork()));
        $this->assertFalse($subnet2->isBroadcastAddress($subnet2->getNetwork()));
        $this->assertFalse($subnet3->isBroadcastAddress($subnet3->getNetwork()));
        $this->assertFalse($subnet4->isBroadcastAddress($subnet4->getNetwork()));
    }

    public function testIsValidAddress()
    {
        $subnet1 = new Subnet('192.168.1.1', 32);
        $subnet2 = new Subnet('192.168.1.0', 24);
        $subnet3 = new Subnet('192.168.0.0', 16);
        $subnet4 = new Subnet('0.0.0.0', 0);

        $this->assertFalse($subnet1->isValidAddress($subnet1->getNetwork()));
        $this->assertFalse($subnet2->isValidAddress($subnet2->getNetwork()));
        $this->assertFalse($subnet3->isValidAddress($subnet3->getNetwork()));
        $this->assertFalse($subnet4->isValidAddress($subnet4->getNetwork()));

        $this->assertFalse($subnet1->isValidAddress($subnet1->getBroadcastAddress()));
        $this->assertFalse($subnet2->isValidAddress($subnet2->getBroadcastAddress()));
        $this->assertFalse($subnet3->isValidAddress($subnet3->getBroadcastAddress()));
        $this->assertFalse($subnet4->isValidAddress($subnet4->getBroadcastAddress()));

        $this->assertFalse($subnet1->isValidAddress($subnet1->getNetwork()->getNext()));
        $this->assertTrue($subnet2->isValidAddress($subnet2->getNetwork()->getNext()));
        $this->assertTrue($subnet3->isValidAddress($subnet3->getNetwork()->getNext()));
        $this->assertTrue($subnet4->isValidAddress($subnet4->getNetwork()->getNext()));

        $this->assertFalse($subnet1->isValidAddress($subnet1->getBroadcastAddress()->getPrevious()));
        $this->assertTrue($subnet2->isValidAddress($subnet2->getBroadcastAddress()->getPrevious()));
        $this->assertTrue($subnet3->isValidAddress($subnet3->getBroadcastAddress()->getPrevious()));
        $this->assertTrue($subnet4->isValidAddress($subnet4->getBroadcastAddress()->getPrevious()));
    }

    public function testFactory()
    {
        $subnet = new Subnet('192.186.0.0', 16);

        $this->assertEquals($subnet, Subnet::factory($subnet));
        $this->assertEquals($subnet, Subnet::factory($subnet->getNetwork(), $subnet->getMask()));
        $this->assertEquals($subnet, Subnet::factory('192.186.0.0', 16));
        $this->assertFalse($subnet === Subnet::factory($subnet), '$subnet === Subnet::factory($subnet)');
    }
}
