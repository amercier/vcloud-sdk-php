<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class UnknownClassField extends VCloudException
{
    public function __construct($className, $fieldName)
    {
        parent::__construct('Unknown field ' . $fieldName . ' in class ' . $className);
    }
}
