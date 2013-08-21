<?php

namespace VMware\VCloud\Test\Unit;

use VMware\VCloud\Test\ConfigurableTestCase;
use VMware\VCloud\Credentials;

class CredentialsTest extends ConfigurableTestCase
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

    /**
     * @expectedException \VMware\VCloud\Exception\MissingParameter
     */
    public function testConstructFromArrayWithMissingOrganization()
    {
        new Credentials(
            array(
                'username' => $this->config['cloudadmin']['username'],
                'password' => $this->config['cloudadmin']['password'],
            )
        );
    }

    /**
     * @expectedException \VMware\VCloud\Exception\MissingParameter
     */
    public function testConstructFromArrayWithMissingUsername()
    {
        new Credentials(
            array(
                'organization' => $this->config['cloudadmin']['organization'],
                'password' => $this->config['cloudadmin']['password'],
            )
        );
    }

    /**
     * @expectedException \VMware\VCloud\Exception\MissingParameter
     */
    public function testConstructFromArrayWithMissingPassword()
    {
        new Credentials(
            array(
                'organization' => $this->config['cloudadmin']['organization'],
                'username' => $this->config['cloudadmin']['username'],
            )
        );
    }

    public function testToArray()
    {
        $credentials = new Credentials(
            $this->config['cloudadmin']['organization'],
            $this->config['cloudadmin']['username'],
            $this->config['cloudadmin']['password']
        );

        $array = $credentials->toArray();

        $this->assertArrayHasKey('username', $array);
        $this->assertArrayHasKey('password', $array);
    }
}
