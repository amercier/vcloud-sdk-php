<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

use VMware\VCloud\Ip\Address;
use VMware\VCloud\Ip\Mask;

class MaskTest extends VCloudTest
{
    public function testConstruct()
    {
        $masks = array();
        for ($i = 0 ; $i <= 32 ; $i++) {
            $masks[$i] = new Mask($i);
        }

        $mask11 = new Mask(24);
        $mask12 = new Mask('24');
        $mask13 = new Mask('255.255.255.0');

        $mask21 = new Mask(32);
        $mask22 = new Mask('32');
        $mask23 = new Mask('255.255.255.255');

        $mask31 = new Mask(0);
        $mask32 = new Mask('0');
        $mask33 = new Mask('0.0.0.0');

        $this->assertTrue($mask11 instanceof Mask, "new Mask(24) instanceof Mask");
        $this->assertTrue($mask12 instanceof Mask, "new Mask('24') instanceof Mask");
        $this->assertTrue($mask13 instanceof Mask, "new Mask('255.255.255.0') instanceof Mask");

        $this->assertTrue($mask21 instanceof Mask, "new Mask(32) instanceof Mask");
        $this->assertTrue($mask22 instanceof Mask, "new Mask('32') instanceof Mask");
        $this->assertTrue($mask23 instanceof Mask, "new Mask('255.255.255.255') instanceof Mask");

        $this->assertTrue($mask31 instanceof Mask, "new Mask(0) instanceof Mask");
        $this->assertTrue($mask32 instanceof Mask, "new Mask('0') instanceof Mask");
        $this->assertTrue($mask33 instanceof Mask, "new Mask('0.0.0.0') instanceof Mask");

        for ($i = 0 ; $i <= 32 ; $i++) {
        	$this->assertEquals($i === 0 ? 0 : Address::getLastAddress()->getAddress() << (32 - $i), $masks[$i]->getMask(), 'new Mask(' + $i + ')->getMask() === Address::getLastAddress()->getAddress() << ' + (32 - $i) + ' === ' + ($i === 0 ? 0 : Address::getLastAddress()->getAddress() << (32 - $i)));
        }

	    $this->assertEquals($mask11, $mask12, "new Mask('24') === new Mask(24)");
	    $this->assertEquals($mask11, $mask13, "new Mask('255.255.255.0') === new Mask(24)");

	    $this->assertEquals($mask21, $mask22, "new Mask('32') === new Mask(32)");
	    $this->assertEquals($mask21, $mask23, "new Mask('255.255.255.255') === new Mask(32)");

	    $this->assertEquals($mask31, $mask32, "new Mask('0') === new Mask(0)");
	    $this->assertEquals($mask31, $mask33, "new Mask('0.0.0.0') === new Mask(0)");
	}

	public function testToString()
	{
        $mask1 = new Mask('255.255.255.0');
        $mask2 = new Mask('0.0.0.0');
        $mask3 = new Mask('255.255.255.255');
        $mask4 = new Mask('192.168.0.14');

        $this->assertEquals('255.255.255.0'  , '' . $mask1, "new Mask('255.255.255.0').toString() === '255.255.255.0'");
        $this->assertEquals('0.0.0.0'        , '' . $mask2, "new Mask('0.0.0.0').toString() === '0.0.0.0'");
        $this->assertEquals('255.255.255.255', '' . $mask3, "new Mask('255.255.255.255').toString() === '255.255.255.255'");
        $this->assertEquals('192.168.0.14'   , '' . $mask4, "new Mask('192.168.0.14').toString() === '192.168.0.14'");
	}

    public function testGetMaskSize()
    {
        $masks = array();
        for ($i = 0 ; $i <= 32 ; $i++) {
            $masks[$i] = new Mask($i);
        }

        for ($i = 0 ; $i <= 32 ; $i++) {
            $this->assertEquals($i, $masks[$i]->getMaskSize(), 'new Mask(' + $i + ')->getMaskSize() === ' + $i);
        }
    }

    public function testApply()
    {
        $mask1    = new Mask(16);
        $address1 = new Address('192.168.0.0');

        $this->assertEquals($address1, $mask1->apply('192.168.0.1')  , "new Mask(16)->apply('192.168.0.1') === '192.168.0.0'");
        $this->assertEquals($address1, $mask1->apply('192.168.0.255'), "new Mask(16)->apply('192.168.0.255') === '192.168.0.0'");
        $this->assertEquals($address1, $mask1->apply('192.168.1.0')  , "new Mask(16)->apply('192.168.1.0') === '192.168.0.0'");
        $this->assertEquals($address1, $mask1->apply('192.168.1.254'), "new Mask(16)->apply('192.168.1.254') === '192.168.0.0'");

        $this->assertEquals($address1, $mask1->apply(new Address('192.168.0.1')  ), "new Mask(16)->apply('192.168.0.1') === '192.168.0.0'");
        $this->assertEquals($address1, $mask1->apply(new Address('192.168.0.255')), "new Mask(16)->apply('192.168.0.255') === '192.168.0.0'");
        $this->assertEquals($address1, $mask1->apply(new Address('192.168.1.0')  ), "new Mask(16)->apply('192.168.1.0') === '192.168.0.0'");
        $this->assertEquals($address1, $mask1->apply(new Address('192.168.1.254')), "new Mask(16)->apply('192.168.1.254') === '192.168.0.0'");
    }

    /**
     * @expectedException \VMware\VCloud\Exception\InvalidMaskSize
     */
    public function testConstructWithMaskSizeMinus1()
    {
        new Mask(-1);
    }

    /**
     * @expectedException \VMware\VCloud\Exception\InvalidMaskSize
     */
    public function testConstructWithMaskSize33()
    {
        new Mask(33);
    }
}
