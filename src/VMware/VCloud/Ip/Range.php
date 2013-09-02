<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Object as Object;
use \VMware\VCloud\Exception as Exception;

class Range extends Object
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->set('start', Address::factory($start));
        $this->set('end', Address::factory($end));

        if ($this->getStart()->isAfter($this->getEnd())) {
            throw new Exception\InvalidRange(
                Address::factory($start),
                Address::factory($end),
                'End address is before start address'
            );
        }
    }

    public function getStart()
    {
        return $this->get('start');
    }

    public function getEnd()
    {
        return $this->get('end');
    }

    public function contains($address)
    {
        $realAddress = Address::factory($address);
        return !$realAddress->isBefore($this->getStart()) && !$realAddress->isAfter($this->getEnd());
    }

    public function intersects(Range $range)
    {
        return !($this->getStart()->isAfter($range->getEnd()) || $this->getEnd()->isBefore($range->getStart()));
    }

    public function belongsTo(Subnet $subnet)
    {
        return $subnet->isValidAddress($this->getStart()) && $subnet->isValidAddress($this->getEnd());
    }

    public function __toString()
    {
        return $this->getStart() . ' - ' . $this->getEnd();
    }

    public static function factory($startOrRange, $end = null)
    {
        return
            $startOrRange instanceof self
            ? new self($startOrRange->getStart(), $startOrRange->getEnd())
            : new self($startOrRange, Address::factory($end));
    }
}
