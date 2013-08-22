<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;
use VMware\VCloud\Ip\Address;
use VMware\VCloud\Ip\Mask;
use VMware\VCloud\Ip\Subnet;

class InvalidSubnet extends VCloudException
{
    public function __construct(Address $network, Mask $mask)
    {
        $expectedNetwork = $mask->apply($network);
        parent::__construct(
            'Invalid IPv4 Subnet "' . $network . '/'
            . $mask->getMaskSize() . '", should be "'
            . new Subnet($expectedNetwork, $mask) . '".'
        );
    }
}
