<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class ObjectNotFound extends VCloudException
{
    public function __construct($objectType, $criteria, $name, $location = false)
    {
        parent::__construct(
            ucfirst($objectType) . ' with ' . $criteria
            . ' "' . $name . '" does not exist or is not visible'
            . ($location ? ' in ' . $location : '')
        );
    }
}
