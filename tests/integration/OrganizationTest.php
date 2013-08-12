<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

use VMware\VCloud\Service;

class OrganizationTest extends \VCloudTest
{
    public function testNetworks()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['orgadmin']);

        $networks = $service->getCurrentOrganization()->getNetworks();
        $this->assertNotEquals(0, count($networks));
    }
}
