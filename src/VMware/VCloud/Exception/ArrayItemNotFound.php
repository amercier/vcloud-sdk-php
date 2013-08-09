<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class ArrayItemNotFound extends VCloudException
{
    public function __construct($fieldName, $value)
    {
        parent::__construct('Item  with value "' . $value . '"" not found in ' . $fieldName);
    }
}
