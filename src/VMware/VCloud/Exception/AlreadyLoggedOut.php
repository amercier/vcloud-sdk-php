<?php

namespace VMware\VCloud\Exception;

use Cli\Helpers\Exception as VCloudException;

class AlreadyLoggedOut extends VCloudException
{
    public function __construct()
    {
        parent::__construct('Trying to log out without being logged in');
    }
}
