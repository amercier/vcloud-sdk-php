<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class InvalidBitMask extends VCloudException
{
    public function __construct($bitMask)
    {
        parent::__construct('Bit mask ' . $bitMask . ' is invalid');
    }
}
