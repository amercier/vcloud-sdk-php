<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

abstract class VCloudTest extends PHPUnit_Framework_TestCase
{
    const CONFIG_FILE = '/config.json';

    protected $config = null;

    public function setUp()
    {
        $this->config = json_decode( file_get_contents(dirname(__FILE__) . self::CONFIG_FILE), true );
    }
}
