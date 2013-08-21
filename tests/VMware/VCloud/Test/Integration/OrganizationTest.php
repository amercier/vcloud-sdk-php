<?php

namespace VMware\VCloud\Test\Integration;

use VMware\VCloud\Service;

class OrganizationTest extends ConfigurableTestCase
{
    public function testNetworks()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['orgadmin']);

        $networks = $service->getCurrentOrganization()->getNetworks();
        $this->assertNotEquals(0, count($networks));
    }

    public function testVirtualDatacenters()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['orgadmin']);

        $virtualDataCenters = $service->getCurrentOrganization()->getVirtualDataCenters();
        $this->assertNotEquals(0, count($virtualDataCenters));
    }
}
