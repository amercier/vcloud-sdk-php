<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;
use VMware\VCloud\Ip\BitMask;

class InvalidMask extends VCloudException
{
    public function __construct(BitMask $mask)
    {
        parent::__construct('Bit mask ' . $mask . ' is not a valid network mask');
    }
}
