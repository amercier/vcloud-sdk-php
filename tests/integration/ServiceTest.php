<?php


use VMware\VCloud\Credentials;
use VMware\VCloud\Service;

class ServiceTest extends PHPUnit_Framework_TestCase
{
    const CONFIG_FILE = '/../config.json';

    static $config = null;

    public static function setUpBeforeClass()
    {
        self::$config = json_decode( file_get_contents(dirname(__FILE__) . self::CONFIG_FILE), true );
    }

    public function testConstructWithCredentials()
    {
        $credentials = new Credentials(
            self::$config['cloudadmin']['organization'],
            self::$config['cloudadmin']['username'],
            self::$config['cloudadmin']['password']
        );
        $this->assertTrue(true);
    }
}
