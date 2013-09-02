<?php

namespace VMware\VCloud\Test\Unit\Ip;

use VMware\VCloud\Test\ConfigurableTestCase;
use VMware\VCloud\Ip\Address;
use VMware\VCloud\Ip\Mask;
use VMware\VCloud\Ip\Range;
use VMware\VCloud\Ip\Subnet;

class RangeTest extends ConfigurableTestCase
{
    public function testConstructWithString()
    {
        $range1 = new Range('192.168.0.0', '192.168.255.255');
        $range2 = new Range('192.168.1.0', '192.168.1.255');

        $this->assertTrue($range1 instanceof Range);
        $this->assertTrue($range2 instanceof Range);
    }

    public function testConstructWithObjects()
    {
        $range1 = new Range(new Address('192.168.0.0'), new Address('192.168.255.255'));
        $range2 = new Range(new Address('192.168.1.0'), new Address('192.168.1.255'));

        $this->assertTrue($range1 instanceof Range);
        $this->assertTrue($range2 instanceof Range);
    }

    /**
     * @expectedException \VMware\VCloud\Exception\InvalidRange
     */
    public function testConstructWithInvalidRange()
    {
        new Range('192.168.255.255', '192.168.0.0');
    }

    public function testGetStart()
    {
        $address1 = new Address('192.168.0.0');
        $range1 = new Range($address1, '192.168.255.255');

        $this->assertEquals($address1, $range1->getStart());
    }

    public function testGetEnd()
    {
        $address1 = new Address('192.168.255.255');
        $range1 = new Range('192.168.0.0', $address1);

        $this->assertEquals($address1, $range1->getEnd());
    }

    public function testContainsWithString()
    {
        $range1 = new Range('192.168.0.0', '192.168.255.255');
        $range2 = new Range('10.170.1.0', '10.170.1.255');

        $this->assertFalse($range1->contains('192.167.255.255'));
        $this->assertTrue($range1->contains('192.168.0.0'));
        $this->assertTrue($range1->contains('192.168.0.1'));
        $this->assertTrue($range1->contains('192.168.255.254'));
        $this->assertTrue($range1->contains('192.168.255.255'));
        $this->assertFalse($range1->contains('192.169.0.0'));

        $this->assertFalse($range2->contains('10.170.0.255'));
        $this->assertTrue($range2->contains('10.170.1.0'));
        $this->assertTrue($range2->contains('10.170.1.1'));
        $this->assertTrue($range2->contains('10.170.1.254'));
        $this->assertTrue($range2->contains('10.170.1.255'));
        $this->assertFalse($range2->contains('10.170.2.0'));
    }

    public function testContainsWithObjects()
    {
        $range1 = new Range('192.168.0.0', '192.168.255.255');
        $range2 = new Range('10.170.1.0', '10.170.1.255');

        $this->assertFalse($range1->contains(new Address('192.167.255.255')));
        $this->assertTrue($range1->contains(new Address('192.168.0.0')));
        $this->assertTrue($range1->contains(new Address('192.168.0.1')));
        $this->assertTrue($range1->contains(new Address('192.168.255.254')));
        $this->assertTrue($range1->contains(new Address('192.168.255.255')));
        $this->assertFalse($range1->contains(new Address('192.169.0.0')));

        $this->assertFalse($range2->contains(new Address('10.170.0.255')));
        $this->assertTrue($range2->contains(new Address('10.170.1.0')));
        $this->assertTrue($range2->contains(new Address('10.170.1.1')));
        $this->assertTrue($range2->contains(new Address('10.170.1.254')));
        $this->assertTrue($range2->contains(new Address('10.170.1.255')));
        $this->assertFalse($range2->contains(new Address('10.170.2.0')));
    }

    public function testIntersects()
    {
        $range1 = new Range('192.168.0.0', '192.168.255.255');
        $range2 = new Range('10.170.1.0', '10.170.1.255');

        foreach (array(

            array($range1, new Range('192.167.0.0', '192.167.255.255'), false),
            array($range1, new Range('192.169.0.0', '192.169.1.255'), false),
            array($range1, new Range('192.167.0.0', '192.168.0.255'), true),
            array($range1, new Range('192.167.0.0', '192.168.0.0'), true),
            array($range1, new Range('192.168.255.0', '192.169.1.255'), true),
            array($range1, new Range('192.168.255.255', '192.169.1.255'), true),
            array($range1, new Range('192.168.1.0', '192.168.254.255'), true),
            array($range1, new Range('192.168.0.0', '192.168.1.255'), true),
            array($range1, new Range('192.167.0.0', '192.169.1.255'), true),

            array($range2, new Range('10.170.0.0', '10.170.0.255'), false),
            array($range2, new Range('10.170.2.0', '10.170.2.255'), false),
            array($range2, new Range('10.170.0.0', '10.170.1.100'), true),
            array($range2, new Range('10.170.0.0', '10.170.1.0'), true),
            array($range2, new Range('10.170.1.200', '10.170.2.255'), true),
            array($range2, new Range('10.170.1.255', '10.170.2.255'), true),
            array($range2, new Range('10.170.1.100', '10.170.1.200'), true),
            array($range2, new Range('10.170.1.0', '10.170.1.255'), true),
            array($range2, new Range('10.170.0.0', '10.170.2.255'), true),

        ) as $testCase) {
            $refRange = $testCase[0];
            $caseRange = $testCase[1];
            if ($testCase[2]) {
                $this->assertTrue($refRange->intersects($caseRange), $refRange . ' must intersect ' . $caseRange);
                $this->assertTrue($caseRange->intersects($refRange), $caseRange . ' must intersect ' . $refRange);
            } else {
                $this->assertFalse($refRange->intersects($caseRange), $refRange . ' must not intersect ' . $caseRange);
                $this->assertFalse($caseRange->intersects($refRange), $caseRange . ' must not intersect ' . $refRange);
            }
        }
    }

    public function testBelongsTo()
    {
        $range1 = new Range('192.168.0.1', '192.168.255.254');
        $range2 = new Range('10.170.1.1', '10.170.1.254');

        $subnet1 = new Subnet('192.168.0.0', 24);
        $subnet2 = new Subnet('192.168.0.0', 16);
        $subnet3 = new Subnet('192.0.0.0', 8);
        $subnet4 = new Subnet('10.170.1.0', 24);
        $subnet5 = new Subnet('10.170.0.0', 16);
        $subnet6 = new Subnet('0.0.0.0', 0);

        $this->assertFalse($range1->belongsTo($subnet1), $range1 . ' must not belong to ' . $subnet1);
        $this->assertTrue($range1->belongsTo($subnet2), $range1 . ' must belong to ' . $subnet2);
        $this->assertTrue($range1->belongsTo($subnet3), $range1 . ' must belong to ' . $subnet3);
        $this->assertFalse($range1->belongsTo($subnet4), $range1 . ' must not belong to ' . $subnet4);
        $this->assertTrue($range1->belongsTo($subnet6), $range1 . ' must belong to ' . $subnet6);

        $this->assertTrue($range2->belongsTo($subnet4), $range2 . ' must belong to ' . $subnet4);
        $this->assertTrue($range2->belongsTo($subnet5), $range2 . ' must belong to ' . $subnet5);
        $this->assertFalse($range2->belongsTo($subnet1), $range2 . ' must not belong to ' . $subnet1);
        $this->assertTrue($range2->belongsTo($subnet6), $range2 . ' must belong to ' . $subnet6);
    }

    public function testToString()
    {
        $range1 = new Range('192.168.0.1', '192.168.255.254');
        $range2 = new Range('10.170.1.1', '10.170.1.254');

        $this->assertEquals('192.168.0.1 - 192.168.255.254', ''.$range1);
        $this->assertEquals('10.170.1.1 - 10.170.1.254', ''.$range2);
    }

    public function testRange()
    {
        $range = new Range('192.168.0.1', '192.168.255.254');

        $this->assertEquals($range, Range::factory($range));
        $this->assertEquals($range, Range::factory($range->getStart(), $range->getEnd()));
        $this->assertEquals($range, Range::factory('192.168.0.1', '192.168.255.254'));
        $this->assertFalse($range === Range::factory($range), '$range === Range::factory($range)');
    }
}
