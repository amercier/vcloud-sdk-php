<?php

namespace VMware\VCloud;

/**
 * Abstract exception class for this project. Parent class of all other
 * exceptions.
 *
 * All VMware\VCloud\Exception\* exception classes extend this class. This makes
 * catching a bit easier as you can catch all VMware\VCloud\Exception\* exceptions
 * with a single:
 *
 *     catch (VMware\VCloud\Exception $e) {
 *         ...
 *     }
 *
 * instead of having to catch all exceptions individually.
 */
abstract class Exception extends \Exception
{
}
