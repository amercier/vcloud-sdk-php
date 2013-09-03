<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

use VMware\VCloud\Ip\Range;
use VMware\VCloud\Ip\Subnet;

class RangeOverlap extends VCloudException
{
    public function __construct(Range $newRange, Range $existingRange)
    {
        parent::__construct('Range ' . $newRange . ' overlaps with range ' . $existingRange);
    }
}
