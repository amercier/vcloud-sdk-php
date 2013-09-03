<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;
use VMware\VCloud\Ip\Subnet;
use VMware\VCloud\Ip\Address;

class AddressOutOfSubnet extends VCloudException
{
    public function __construct(Subnet $subnet, Address $address)
    {
        parent::__construct('Address ' . $address . ' is out of subnet ' . $subnet);
    }
}
