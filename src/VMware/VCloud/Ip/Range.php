<?php

namespace VMware\VCloud\Ip;

use \VMware\VCloud\Object as Object;
use \VMware\VCloud\Exception as Exception;

class Range extends Object
{
    protected $start;
    protected $end;

    public function __construct(Address $start, Address $end)
    {
        $this->set('start', $start);
        $this->set('end', $end);

        if ($start->isAfter($end)) {
            throw new Exception\InvalidRange($start, $end, 'End address is before start address');
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

    public function contains(Address $address)
    {
        return !$address->isBefore($this->getStart()) && !$address->isAfter($this->getEnd());
    }

    public function intersects(Range $range)
    {
        return $this->getStart()->isAfter($range->getEnd()) || $this->getEnd()->isBefore($range->getStart());
    }
}
