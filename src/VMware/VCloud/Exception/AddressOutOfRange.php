<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;
use VMware\VCloud\Ip\Range;
use VMware\VCloud\Ip\Address;

class AddressOutOfRange extends VCloudException
{
    public function __construct(Range $range, Address $address)
    {
        parent::__construct('Address ' . $address . ' is out of range ' . $range);
    }
}
