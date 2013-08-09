<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class ClassFieldNotArray extends VCloudException
{
    public function __construct($className, $fieldName)
    {
        parent::__construct('Field ' . $fieldName . ' is not an array in class ' . $className);
    }
}
