<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;
use VMware\VCloud\Ip\Pool;

class FullPool extends VCloudException
{
    public function __construct(Pool $pool)
    {
        parent::__construct('Pool ' . $pool . ' is full');
    }
}
