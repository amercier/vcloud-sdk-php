<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

use VMware\VCloud\Http\Host;

class HostTest extends VCloudTest
{
    public function testConstructWithFQDN()
    {
        new Host('server.example.org');
        $this->assertTrue(true);
    }

    public function testConstructWithIP()
    {
        new Host('192.168.0.1');
        $this->assertTrue(true);
    }

    public function testGetUrl()
    {
        $host = new Host('server.example.org');
        $this->assertEquals($host->getUrl(), 'server.example.org');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\MalformedUrl
     */
    public function testMalformedUrl()
    {
        new Host('mer il et fou');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\MalformedUrl
     */
    public function testUrlWithScheme()
    {
        new Host('http://server.example.org');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\MalformedUrl
     */
    public function testUrlWithPort()
    {
        new Host('server.example.org:443');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\MalformedUrl
     */
    public function testUrlWithPath()
    {
        new Host('server.example.org/test');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\MalformedUrl
     */
    public function testUrlWithQuery()
    {
        new Host('server.example.org?ok=false');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\MalformedUrl
     */
    public function testUrlWithFragment()
    {
        new Host('server.example.org#nok');
    }
}
