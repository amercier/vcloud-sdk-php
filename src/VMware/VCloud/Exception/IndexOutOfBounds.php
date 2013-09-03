<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class IndexOutOfBounds extends VCloudException
{
    public function __construct($fieldName, $index, $size)
    {
        parent::__construct(
            'Trying to access item of ' . $fieldName
            . ' at index #' . $index . ', while array size is ' . $size
        );
    }
}
