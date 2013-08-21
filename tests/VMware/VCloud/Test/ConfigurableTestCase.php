<?php

namespace VMware\VCloud\Test;

abstract class ConfigurableTestCase extends \PHPUnit_Framework_TestCase
{
    const CONFIG_FILE = '/config.json';

    protected $config = null;

    public function setUp()
    {
        if (file_exists(dirname(__FILE__) . self::CONFIG_FILE)) {
            $this->config = json_decode(file_get_contents(dirname(__FILE__) . self::CONFIG_FILE), true);
        }
    }

    public function assertArrayContains($expected, $actual, $message = '')
    {
        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $actual, $message);
            $this->assertContains($value, $actual, $message);
        }
    }
}
