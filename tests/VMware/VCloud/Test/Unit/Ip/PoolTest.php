<?php

namespace VMware\VCloud\Test\Unit\Ip;

use VMware\VCloud\Test\ConfigurableTestCase;
use VMware\VCloud\Ip\Address;
use VMware\VCloud\Ip\Mask;
use VMware\VCloud\Ip\Subnet;
use VMware\VCloud\Ip\Range;
use VMware\VCloud\Ip\Pool;

class PoolTest extends ConfigurableTestCase
{
    public function testConstruct()
    {
        $pool11 = new Pool(new Subnet('192.168.1.0', 24));
        $pool12 = new Pool(new Subnet('192.168.1.0', 24), array(new Range('192.168.1.1', '192.168.1.254')));
        $pool13 = new Pool(new Subnet('192.168.1.0', 24), array(new Range('192.168.1.1', '192.168.1.100')));
        $pool14 = new Pool(new Subnet('192.168.1.0', 24), array(new Range('192.168.1.100', '192.168.1.254')));
        $pool15 = new Pool(new Subnet('192.168.1.0', 24), array(new Range('192.168.1.100', '192.168.1.200')));
        $pool21 = new Pool(new Subnet('172.0.0.0', 16));
        $pool22 = new Pool(new Subnet('172.0.0.0', 16), array());
    }

    public function testGetSubnet()
    {
        $subnet = new Subnet('192.168.1.0', 24);
        $pool = new Pool($subnet);
        $this->assertEquals($subnet, $pool->getSubnet());
    }

    public function testAddRange()
    {
        $subnet = new Subnet('192.168.1.0', 24);
        $pool = new Pool($subnet);
        $range1 = new Range('192.168.1.1', '192.168.1.10');
        $range2 = new Range('192.168.1.21', '192.168.1.30');
        $pool->addRange($range1);
        $this->assertEquals(implode(' ; ', array($range1)), implode(' ; ', $pool->getRanges()));
        $pool->addRange($range2);
        $this->assertEquals(implode(' ; ', array($range1, $range2)), implode(' ; ', $pool->getRanges()));
    }

    public function testAddRangeOrder()
    {
        $subnet = new Subnet('192.168.1.0', 24);
        $pool = new Pool($subnet);

        $range1 = new Range('192.168.1.1', '192.168.1.10');
        $range2 = new Range('192.168.1.21', '192.168.1.30');
        $range3 = new Range('192.168.1.31', '192.168.1.40');
        $range4 = new Range('192.168.1.61', '192.168.1.70');

        $pool->addRanges(array($range3, $range1));
        $this->assertEquals(
            implode(' ; ', array($range1, $range3)),
            implode(' ; ', $pool->getRanges())
        );

        $pool->addRanges(array($range4, $range2));
        $this->assertEquals(
            implode(' ; ', array($range1, $range2, $range3, $range4)),
            implode(' ; ', $pool->getRanges())
        );
    }

    /**
     * @expectedException \VMware\VCloud\Exception\RangeOutOfSubnet
     */
    public function testAddRangeOutOfSubnet()
    {
        $pool = new Pool(new Subnet('192.168.1.0', 24));
        $pool->addRange(new Range('192.168.2.1', '192.168.2.254'));
    }

    /**
     * @expectedException \VMware\VCloud\Exception\RangeOverlap
     */
    public function testAddRangeTwice()
    {
        $subnet = new Subnet('192.168.1.0', 24);
        $pool = new Pool($subnet);
        $range = new Range('192.168.1.1', '192.168.1.10');

        $pool->addRange($range)->addRange($range);
    }

    /**
     * @expectedException \VMware\VCloud\Exception\RangeOverlap
     */
    public function testAddOverlappingRange()
    {
        $subnet = new Subnet('192.168.1.0', 24);
        $pool = new Pool($subnet);
        $range1 = new Range('192.168.1.1', '192.168.1.100');
        $range2 = new Range('192.168.1.51', '192.168.1.150');

        $pool->addRange($range1)->addRange($range2);
    }

    public function testAllowOverlapping()
    {
        $pool = new Pool(new Subnet('192.168.1.0', 24), array(), array(), true);
        $this->assertTrue($pool->allowsOverlapping());
    }

    public function testAddRangeTwiceAllowed()
    {
        $subnet = new Subnet('192.168.1.0', 24);
        $pool = new Pool($subnet, array(), array(), true);
        $range1 = new Range('192.168.1.1', '192.168.1.10');

        $pool->addRange($range1);
        $this->assertEquals(array($range1), $pool->getRanges());
        $pool->addRange($range1);
        $this->assertEquals(array($range1, $range1), $pool->getRanges());
    }

    public function testAddOverlappingRangeAllowed()
    {
        $subnet = new Subnet('192.168.1.0', 24);
        $pool = new Pool($subnet, array(), array(), true);
        $range1 = new Range('192.168.1.1', '192.168.1.100');
        $range2 = new Range('192.168.1.51', '192.168.1.150');

        $pool->addRange($range1);
        $this->assertEquals(array($range1), $pool->getRanges());
        $pool->addRange($range2);
        $this->assertEquals(array($range1, $range2), $pool->getRanges());
    }

    public function testAddRanges()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $ranges = array(new Range('192.168.1.1', '192.168.1.10'), new Range('192.168.1.21', '192.168.1.30'));

        $pool->addRanges($ranges);
        $this->assertEquals($ranges, $pool->getRanges());
    }

    public function testAddOverlappingRanges()
    {
        $subnet = new Subnet('192.168.1.0', 24);
        $pool = new Pool($subnet, array(), array(), true);
        $ranges = array(
            new Range('192.168.1.1', '192.168.1.100'),
            new Range('192.168.1.51', '192.168.1.150')
        );

        $pool->addRanges($ranges);
    }

    public function testContains()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.10'),
                new Range('192.168.1.21', '192.168.1.30')
            )
        );

        $this->assertFalse($pool->contains('192.168.1.1'));
        $this->assertTrue($pool->contains('192.168.1.2'));
        $this->assertTrue($pool->contains('192.168.1.5'));
        $this->assertTrue($pool->contains('192.168.1.10'));
        $this->assertFalse($pool->contains('192.168.1.11'));
        $this->assertFalse($pool->contains('192.168.1.20'));
        $this->assertTrue($pool->contains('192.168.1.21'));
        $this->assertTrue($pool->contains('192.168.1.25'));
        $this->assertTrue($pool->contains('192.168.1.30'));
        $this->assertFalse($pool->contains('192.168.1.31'));
    }

    /**
     * @expectedException \VMware\VCloud\Exception\AddressOutOfSubnet
     */
    public function testContainsAddressOutOfSubnet()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.10'),
                new Range('192.168.1.21', '192.168.1.30')
            )
        );

        $pool->contains('192.168.1.0');
    }

    public function testAllocateAddress()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.10'),
                new Range('192.168.1.21', '192.168.1.30')
            )
        );

        foreach (array(
            '192.168.1.2',
            '192.168.1.5',
            '192.168.1.10',
            '192.168.1.21',
            '192.168.1.25',
            '192.168.1.30',
        ) as $address) {
            $this->assertFalse($pool->isAllocated($address));
            $this->assertTrue($pool->isAvailable($address));
            $pool->allocate($address);
            $this->assertTrue($pool->isAllocated($address));
            $this->assertFalse($pool->isAvailable($address));
        }
    }

    /**
     * @expectedException \VMware\VCloud\Exception\AddressOutOfSubnet
     */
    public function testAllocateAddressOutOfSubnet()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.10'),
                new Range('192.168.1.21', '192.168.1.30')
            )
        );

        $pool->allocate('192.168.1.0');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\AddressOutOfPool
     */
    public function testAllocateAddressOutOfPool()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.10'),
                new Range('192.168.1.21', '192.168.1.30')
            )
        );

        $pool->allocate('192.168.1.1');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\AddressOutOfPool
     */
    public function testIsAllocatedAddressOutOfPool()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.10'),
                new Range('192.168.1.21', '192.168.1.30')
            )
        );

        $pool->isAllocated('192.168.1.1');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\AddressOutOfPool
     */
    public function testIsAvailableAddressOutOfPool()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.10'),
                new Range('192.168.1.21', '192.168.1.30')
            )
        );

        $pool->isAvailable('192.168.1.1');
    }

    public function testAllocateAll()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.10'),
                new Range('192.168.1.21', '192.168.1.30')
            )
        );

        $addresses = array(
            '192.168.1.2',
            '192.168.1.5',
            '192.168.1.10',
            '192.168.1.21',
            '192.168.1.25',
            '192.168.1.30',
        );

        foreach ($addresses as $address) {
            $this->assertFalse($pool->isAllocated($address));
            $this->assertTrue($pool->isAvailable($address));
        }

        $pool->allocateAll($addresses);

        foreach ($addresses as $address) {
            $this->assertTrue($pool->isAllocated($address));
            $this->assertFalse($pool->isAvailable($address));
        }
    }

    public function testGetFirstAvailable()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.5'),
                new Range('192.168.1.10', '192.168.1.15'),
            )
        );

        foreach (array(
            new Address('192.168.1.2'),
            new Address('192.168.1.3'),
            new Address('192.168.1.4'),
            new Address('192.168.1.5'),
            new Address('192.168.1.10'),
            new Address('192.168.1.11'),
            new Address('192.168.1.12'),
            new Address('192.168.1.13'),
            new Address('192.168.1.14'),
            new Address('192.168.1.15'),
        ) as $address) {
            $this->assertEquals(''.$address, ''.$pool->getFirstAvailable());
            $pool->allocate($address);
        }
    }

    public function testGetFirstAvailableReversed()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.10', '192.168.1.15'),
                new Range('192.168.1.2', '192.168.1.5'),
            )
        );

        foreach (array(
            new Address('192.168.1.2'),
            new Address('192.168.1.3'),
            new Address('192.168.1.4'),
            new Address('192.168.1.5'),
            new Address('192.168.1.10'),
            new Address('192.168.1.11'),
            new Address('192.168.1.12'),
            new Address('192.168.1.13'),
            new Address('192.168.1.14'),
            new Address('192.168.1.15'),
        ) as $address) {
            $this->assertEquals(''.$address, ''.$pool->getFirstAvailable());
            $pool->allocate($address);
        }
    }

    /**
     * @expectedException \VMware\VCloud\Exception\FullPool
     */
    public function testGetFirstAvailableFullPool()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.5'),
                new Range('192.168.1.10', '192.168.1.15'),
            )
        );

        foreach (array(
            new Address('192.168.1.2'),
            new Address('192.168.1.3'),
            new Address('192.168.1.4'),
            new Address('192.168.1.5'),
            new Address('192.168.1.10'),
            new Address('192.168.1.11'),
            new Address('192.168.1.12'),
            new Address('192.168.1.13'),
            new Address('192.168.1.14'),
            new Address('192.168.1.15'),
        ) as $address) {
            $pool->allocate($address);
        }

        $pool->getFirstAvailable();
    }

    public function testGetLastAvailable()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.5'),
                new Range('192.168.1.10', '192.168.1.15'),
            )
        );

        foreach (array(
            new Address('192.168.1.15'),
            new Address('192.168.1.14'),
            new Address('192.168.1.13'),
            new Address('192.168.1.12'),
            new Address('192.168.1.11'),
            new Address('192.168.1.10'),
            new Address('192.168.1.5'),
            new Address('192.168.1.4'),
            new Address('192.168.1.3'),
            new Address('192.168.1.2'),
        ) as $address) {
            $this->assertEquals(''.$address, ''.$pool->getLastAvailable());
            $pool->allocate($address);
        }
    }

    public function testGetLastAvailableReversed()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.10', '192.168.1.15'),
                new Range('192.168.1.2', '192.168.1.5'),
            )
        );

        foreach (array(
            new Address('192.168.1.15'),
            new Address('192.168.1.14'),
            new Address('192.168.1.13'),
            new Address('192.168.1.12'),
            new Address('192.168.1.11'),
            new Address('192.168.1.10'),
            new Address('192.168.1.5'),
            new Address('192.168.1.4'),
            new Address('192.168.1.3'),
            new Address('192.168.1.2'),
        ) as $address) {
            $this->assertEquals(''.$address, ''.$pool->getLastAvailable());
            $pool->allocate($address);
        }
    }

    /**
     * @expectedException \VMware\VCloud\Exception\FullPool
     */
    public function testGetLastAvailableFullPool()
    {
        $subnet = new Subnet('192.168.1.0', 24, array(), array(), true);
        $pool = new Pool($subnet);
        $pool->addRanges(
            array(
                new Range('192.168.1.2', '192.168.1.5'),
                new Range('192.168.1.10', '192.168.1.15'),
            )
        );

        foreach (array(
            new Address('192.168.1.15'),
            new Address('192.168.1.14'),
            new Address('192.168.1.13'),
            new Address('192.168.1.12'),
            new Address('192.168.1.11'),
            new Address('192.168.1.10'),
            new Address('192.168.1.5'),
            new Address('192.168.1.4'),
            new Address('192.168.1.3'),
            new Address('192.168.1.2'),
        ) as $address) {
            $pool->allocate($address);
        }

        $pool->getLastAvailable();
    }
}
