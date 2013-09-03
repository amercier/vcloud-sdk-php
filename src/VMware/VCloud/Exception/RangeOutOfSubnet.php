<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

use VMware\VCloud\Ip\Range;
use VMware\VCloud\Ip\Subnet;

class RangeOutOfSubnet extends VCloudException
{
    public function __construct(Subnet $subnet, Range $range)
    {
        parent::__construct('Range ' . $range . ' is outside subnet ' . $subnet);
    }
}
