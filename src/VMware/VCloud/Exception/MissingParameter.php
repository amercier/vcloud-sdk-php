<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class MissingParameter extends VCloudException
{
    public function __construct($parameter)
    {
        parent::__construct('Missing parameter ' . $parameter);
    }
}
