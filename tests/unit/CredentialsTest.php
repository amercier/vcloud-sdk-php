<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

use VMware\VCloud\Credentials;

class CredentialsTest extends VCloudTest
{
    public function testConstructFromParameters()
    {
        $credentials = new Credentials(
            $this->config['cloudadmin']['organization'],
            $this->config['cloudadmin']['username'],
            $this->config['cloudadmin']['password']
        );

        $this->assertEquals($this->config['cloudadmin']['organization'], $credentials->getOrganization());
        $this->assertEquals($this->config['cloudadmin']['username'], $credentials->getUsername());
        $this->assertEquals($this->config['cloudadmin']['password'], $credentials->getPassword());
    }

    public function testConstructFromArray()
    {
        $credentials = new Credentials(
            array(
                'organization' => $this->config['cloudadmin']['organization'],
                'username' => $this->config['cloudadmin']['username'],
                'password' => $this->config['cloudadmin']['password'],
            )
        );

        $this->assertEquals($this->config['cloudadmin']['organization'], $credentials->getOrganization());
        $this->assertEquals($this->config['cloudadmin']['username'], $credentials->getUsername());
        $this->assertEquals($this->config['cloudadmin']['password'], $credentials->getPassword());
    }
}