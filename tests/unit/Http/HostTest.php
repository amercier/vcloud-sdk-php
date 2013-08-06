<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

use VMware\VCloud\Http\Host;

class HostTest extends VCloudTest
{
    public function testConstructWithFQDN()
    {
        new Host('http://server.example.org');
        $this->assertTrue(true);
        new Host('https://server.example.org');
        $this->assertTrue(true);
    }

    public function testConstructWithIP()
    {
        new Host('http://192.168.0.1');
        $this->assertTrue(true);
        new Host('https://192.168.0.1');
        $this->assertTrue(true);
    }

    public function testGetUrl()
    {
        $host = new Host('http://server.example.org');
        $this->assertEquals($host->getUrl(), 'http://server.example.org');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\MalformedUrl
     */
    public function testMalformedUrl()
    {
        new Host('mer il et fou');
    }
}
