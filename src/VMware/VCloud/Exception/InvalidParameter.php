<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class InvalidParameter extends VCloudException
{
    public function __construct($value, $expectedType)
    {
        parent::__construct(
            'Expecting parameter to be a '
            . implode(' or ', $expectedType)
            . ', got ' . (gettype($value) === 'object' ? get_class($value) : gettype($value))
        );
    }
}
