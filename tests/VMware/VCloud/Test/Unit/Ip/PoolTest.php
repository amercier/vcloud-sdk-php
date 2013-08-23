<?php

namespace VMware\VCloud\Test\Unit\Ip;

use VMware\VCloud\Test\ConfigurableTestCase;
use VMware\VCloud\Ip\Address;
use VMware\VCloud\Ip\Mask;
use VMware\VCloud\Ip\Subnet;
use VMware\VCloud\Ip\Range;
use VMware\VCloud\Ip\Pool;

class PoolTest extends ConfigurableTestCase
{
    public function testConstruct()
    {
        $pool11 = new Pool(new Subnet('192.168.1.0', 24));
        $pool12 = new Pool(new Subnet('192.168.1.0', 24), array(new Range('192.168.1.1', '192.168.1.254')));
        $pool13 = new Pool(new Subnet('192.168.1.0', 24), array(new Range('192.168.1.1', '192.168.1.100')));
        $pool14 = new Pool(new Subnet('192.168.1.0', 24), array(new Range('192.168.1.100', '192.168.1.254')));
        $pool15 = new Pool(new Subnet('192.168.1.0', 24), array(new Range('192.168.1.100', '192.168.1.200')));
        $pool21 = new Pool(new Subnet('172.0.0.0', 16));
        $pool22 = new Pool(new Subnet('172.0.0.0', 16), array());
    }
}
