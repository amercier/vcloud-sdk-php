<?php

namespace VMware\VCloud\Test\Unit\Ip;

use VMware\VCloud\Test\ConfigurableTestCase;
use VMware\VCloud\Ip\BitMask;
use VMware\VCloud\Ip\Address;
use VMware\VCloud\Ip\Mask;

class MaskTest extends ConfigurableTestCase
{
    const MASK_24 = 0xFFFFFF00;
    const MASK_32 = 0xFFFFFFFF;
    const MASK_00 = 0x00000000;

    const NUMBER_32 = 4294967295;
    const NUMBER_24 = 4294967040;
    const NUMBER_00 = 0;

    public function testConstruct()
    {
        $masks = array();
        for ($i = 0; $i <= 32; $i++) {
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

        for ($i = 0; $i <= 32; $i++) {
            $expected =
                $i === 0
                ? 0
                : BitMask::getUnsignedValue((Address::getLast()->getValue() << (32 - $i)) & BitMask::LAST);
            $this->assertEquals(
                $expected,
                $masks[$i]->getValue(),
                'new Mask(' . $i . ')->getValue() === Address::getLast()->getValue() << '
                . (32 - $i) . ' === ' . $expected
            );
        }

        $this->assertEquals($mask11, $mask12, "new Mask('24') === new Mask(24)");
        $this->assertEquals($mask11, $mask13, "new Mask('255.255.255.0') === new Mask(24)");

        $this->assertEquals($mask21, $mask22, "new Mask('32') === new Mask(32)");
        $this->assertEquals($mask21, $mask23, "new Mask('255.255.255.255') === new Mask(32)");

        $this->assertEquals($mask31, $mask32, "new Mask('0') === new Mask(0)");
        $this->assertEquals($mask31, $mask33, "new Mask('0.0.0.0') === new Mask(0)");
    }

    /**
     * @expectedException \VMware\VCloud\Exception\InvalidMask
     */
    public function testConstructInvalidMask()
    {
        new Mask('192.168.0.1');
    }

    public function testGetValue()
    {
        $mask11 = new Mask(24);
        $mask12 = new Mask('24');
        $mask13 = new Mask('255.255.255.0');

        $mask21 = new Mask(32);
        $mask22 = new Mask('32');
        $mask23 = new Mask('255.255.255.255');

        $mask31 = new Mask(0);
        $mask32 = new Mask('0');
        $mask33 = new Mask('0.0.0.0');

        $this->assertEquals(self::MASK_24, $mask11->getValue());
        $this->assertEquals(self::MASK_24, $mask12->getValue());
        $this->assertEquals(self::MASK_24, $mask13->getValue());
        $this->assertEquals(self::NUMBER_24, $mask11->getValue());
        $this->assertEquals(self::NUMBER_24, $mask12->getValue());
        $this->assertEquals(self::NUMBER_24, $mask13->getValue());

        $this->assertEquals(self::MASK_32, $mask21->getValue());
        $this->assertEquals(self::MASK_32, $mask22->getValue());
        $this->assertEquals(self::MASK_32, $mask23->getValue());
        $this->assertEquals(self::NUMBER_32, $mask21->getValue());
        $this->assertEquals(self::NUMBER_32, $mask22->getValue());
        $this->assertEquals(self::NUMBER_32, $mask23->getValue());

        $this->assertEquals(self::MASK_00, $mask31->getValue());
        $this->assertEquals(self::MASK_00, $mask32->getValue());
        $this->assertEquals(self::MASK_00, $mask33->getValue());
        $this->assertEquals(self::NUMBER_00, $mask31->getValue());
        $this->assertEquals(self::NUMBER_00, $mask32->getValue());
        $this->assertEquals(self::NUMBER_00, $mask33->getValue());
    }

    public function testToString()
    {
        $mask1 = new Mask('255.255.255.0');
        $mask2 = new Mask('0.0.0.0');
        $mask3 = new Mask('255.255.255.255');

        $this->assertEquals(
            '255.255.255.0',
            '' . $mask1,
            "new Mask('255.255.255.0').toString() === '255.255.255.0'"
        );
        $this->assertEquals(
            '0.0.0.0',
            '' . $mask2,
            "new Mask('0.0.0.0').toString() === '0.0.0.0'"
        );
        $this->assertEquals(
            '255.255.255.255',
            '' . $mask3,
            "new Mask('255.255.255.255').toString() === '255.255.255.255'"
        );
    }

    public function testGetMaskSize()
    {
        $masks = array();
        for ($i = 0; $i <= 32; $i++) {
            $masks[$i] = new Mask($i);
        }

        for ($i = 0; $i <= 32; $i++) {
            $this->assertEquals(
                $i,
                $masks[$i]->getMaskSize(),
                'new Mask(' + $i + ')->getMaskSize() === ' + $i
            );
        }
    }

    public function testApply()
    {
        $mask1    = new Mask(16);
        $address1 = new Address('192.168.0.0');

        $this->assertEquals(
            $address1,
            $mask1->apply('192.168.0.1'),
            "new Mask(16)->apply('192.168.0.1') === '192.168.0.0'"
        );
        $this->assertEquals(
            $address1,
            $mask1->apply('192.168.0.255'),
            "new Mask(16)->apply('192.168.0.255') === '192.168.0.0'"
        );
        $this->assertEquals(
            $address1,
            $mask1->apply('192.168.1.0'),
            "new Mask(16)->apply('192.168.1.0') === '192.168.0.0'"
        );
        $this->assertEquals(
            $address1,
            $mask1->apply('192.168.1.254'),
            "new Mask(16)->apply('192.168.1.254') === '192.168.0.0'"
        );

        $this->assertEquals(
            $address1,
            $mask1->apply(new Address('192.168.0.1')),
            "new Mask(16)->apply('192.168.0.1') === '192.168.0.0'"
        );
        $this->assertEquals(
            $address1,
            $mask1->apply(new Address('192.168.0.255')),
            "new Mask(16)->apply('192.168.0.255') === '192.168.0.0'"
        );
        $this->assertEquals(
            $address1,
            $mask1->apply(new Address('192.168.1.0')),
            "new Mask(16)->apply('192.168.1.0') === '192.168.0.0'"
        );
        $this->assertEquals(
            $address1,
            $mask1->apply(new Address('192.168.1.254')),
            "new Mask(16)->apply('192.168.1.254') === '192.168.0.0'"
        );
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


    public function testEquals()
    {
        $addresses1 = array(
            new Mask('255.255.255.0'),
            new Mask('0.0.0.0'),
            new Mask('255.255.255.255'),
        );

        $addresses2 = array(
            new Mask('255.255.255.0'),
            new Mask('0.0.0.0'),
            new Mask('255.255.255.255'),
        );

        for ($i = 0; $i < count($addresses1); $i++) {
            for ($j = 0; $j < count($addresses2); $j++) {
                if ($i === $j) {
                    $this->assertTrue(
                        $addresses1[$i]->equals($addresses2[$j]),
                        $addresses1[$i] . ' must equal ' . $addresses2[$j]
                    );
                    $this->assertTrue(
                        $addresses2[$j]->equals($addresses1[$i]),
                        $addresses2[$j] . ' must equal ' . $addresses1[$i]
                    );
                } else {
                    $this->assertFalse(
                        $addresses1[$i]->equals($addresses2[$j]),
                        $addresses1[$i] . ' must not equal ' . $addresses2[$j]
                    );
                    $this->assertFalse(
                        $addresses2[$j]->equals($addresses1[$i]),
                        $addresses2[$j] . ' must not equal ' . $addresses1[$i]
                    );
                }
            }
        }
    }

    public function testFactory()
    {
        $mask = new Mask(24);

        $this->assertEquals($mask, Mask::factory($mask));
        $this->assertEquals($mask, Mask::factory($mask->getMaskSize()));
        $this->assertFalse($mask === Mask::factory($mask), '$mask === Mask::factory($mask)');
    }
}
