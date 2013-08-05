<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class MissingPHPModule extends VCloudException
{
    public function __construct($moduleName, $message)
    {
        parent::__construct($message . '. This probably means that module ' . $moduleName . ' is not installed.');
    }
}
