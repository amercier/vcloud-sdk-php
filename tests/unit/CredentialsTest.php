<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

use VMware\VCloud\Credentials;

class CredentialsTest extends VCloudTest
{
    public function testConstructFromParameters()
    {

    }

    public function testConstructFromArray()
    {
        $credentials = new Credentials(
            array(
                'username' => $this->config['cloudadmin']['username'],
                'organization' => $this->config['cloudadmin']['organization'],
                'password' => $this->config['cloudadmin']['password'],
            )
        );

        $this->assertEquals($this->config['cloudadmin']['organization'], $credentials->getOrganization());
        $this->assertEquals($this->config['cloudadmin']['username'], $credentials->getUsername());
        $this->assertEquals($this->config['cloudadmin']['password'], $credentials->getPassword());
    }
}
