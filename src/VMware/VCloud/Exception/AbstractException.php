<?php
namespace VMware\VCloud\Exception;

abstract class AbstractException extends Exception
{
    public static function factory( VMware_VCloud_SDK_Exception $exception )
    {

    }
}
