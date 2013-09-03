<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;
use VMware\VCloud\Ip\Pool;
use VMware\VCloud\Ip\Address;

class AddressOutOfPool extends VCloudException
{
    public function __construct(Pool $pool, Address $address)
    {
        parent::__construct('Address ' . $address . ' is out of pool ' . $pool);
    }
}
