<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;
use VMware\VCloud\Ip\Address;

class InvalidRange extends VCloudException
{
    public function __construct(Address $address1, Address $address2, $reason = '')
    {
        parent::__construct(
            'Invalid IPv4 Range "' . $address1 . ' .. ' . $address2 . '"' . ($reason ? '. ' . $reason : '')
        );
    }
}
