<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

use VMware\VCloud\Http\Host;

class HostTest extends VCloudTest
{
    public function testConstructWithFQDN()
    {
        new Host('http://server.example.org');
        new Host('https://server.example.org');
    }

    public function testConstructWithIP()
    {
        new Host('http://192.168.0.1');
        new Host('https://192.168.0.1');
    }

    /**
     * @expectedException \VMware\VCloud\Exception\MalformedUrl
     */
    public function testMalformedUrl()
    {
        new Host('mer il et fou');
    }
}
