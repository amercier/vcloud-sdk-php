<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class InvalidArrayKey extends VCloudException
{
    public function __construct($parameterName, $key, $expected)
    {
        parent::__construct(
            'Expecting key to be one of: '
            . implode(', ', array_keys($expected))
            . ', got' . $key . ' for parameter ' . $parameterName
        );
    }
}
