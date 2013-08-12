<?php

namespace VMware\VCloud\Test\Unit;

require_once dirname(__FILE__) . '/../bootstrap.php';

use VMware\VCloud\Http;
use VMware\VCloud\Service;

class ServiceTest extends \VCloudTest
{
    public function testConstructWithObjectHost()
    {
        new Service(
            new Http\Host('server.example.org'),
            Http\Configuration::getDefaultConfiguration()
        );
        $this->assertTrue(true);
    }

    public function testConstructWithStringHost()
    {
        new Service(
            'server.example.org',
            Http\Configuration::getDefaultConfiguration()
        );
        $this->assertTrue(true);
    }

    public function testConstructWithDefaultConfiguration()
    {
        new Service('server.example.org');
        $this->assertTrue(true);
    }

    /**
     * @expectedException \VMware\VCloud\Exception\InvalidParameter
     */
    public function testConstructWithWrongHost()
    {
        new Service(new \DateTime());
    }

    public function testGetImplementation()
    {
        $service = new Service('server.example.org');
        $this->assertTrue($service->getImplementation() instanceof \VMware_VCloud_SDK_Service);
    }

    public function testIsLoggedIn()
    {
        $service = new Service('server.example.org');
        $this->assertFalse($service->isLoggedIn());
    }

    /**
     * @expectedException \VMware\VCloud\Exception\AlreadyLoggedOut
     */
    public function testLogoutBeforeLogin() {
        $service = new Service('server.example.org');
        $service->logout();
    }
}
