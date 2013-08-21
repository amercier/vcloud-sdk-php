<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class InvalidMaskSize extends VCloudException
{
    public function __construct($maskSize)
    {
        parent::__construct('Expecting mask size to be between 0 and 32, got ' . $maskSize);
    }
}
