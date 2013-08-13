<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

use VMware\VCloud\Credentials;
use VMware\VCloud\Service;

class ServiceTest extends \VCloudTest
{
    public function testLoginAsCloudAdmin()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['cloudadmin']);
        $this->assertTrue($service->isLoggedIn());
    }

    public function testLoginAsOrgAdmin()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['orgadmin']);
        $this->assertTrue($service->isLoggedIn());
    }

    public function testLogout()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['cloudadmin']);
        $this->assertTrue($service->isLoggedIn());
        $service->logout();
        $this->assertFalse($service->isLoggedIn());
    }

    /*
    public function testCreateOrganization()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['cloudadmin']);

        $organization = $service
            ->createOrganization(
                $this->config['organization']['name'],
                $this->config['organization']['fullName'],
                $this->config['organization']['description']
            )
            ->setEMailSMTPServerSettings(null) // optional
            ->setEMailNotificationSettings(new Organization\EMailNotificationSettings(
                strtolower(ORG_NAME) . '@example.org',                          // Sender's email address
                '[' . strtolower($this->config['organization']['name']) . '] ', // Email subject prefix
                array('email1@example.org', 'email2@example.org')               // Send system notification to:
            ))
            ->setLDAPSettings(new Organization\LDAPCustomSettings(
                'SIMPLE', // Authentication method ()
                'ldap@example.org', // User name (null for anonymous)
                'ldappassword', // Password (null for anonymous)
                '' //
            ));

        $organization
            ->createUser(
                $this->config['orgadmin'],
                'Organization Administrator',
                true // LDAP
            );
    }
    */

    public function testGetOrganizationsAsOrgAdmin()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['orgadmin']);
        $organizations = $service->getOrganizations();
        $this->assertEquals(1, count($organizations));
        $this->assertEquals(
            strtolower($service->getCredentials()->getOrganization()),
            strtolower($organizations[0]->getName())
        );
    }

    public function testGetCurrentOrganizationAsOrgAdmin()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['orgadmin']);

        $organizations = $service->getOrganizations();
        $currentOrganization = $service->getCurrentOrganization();
        $this->assertEquals($organizations[0], $currentOrganization);
        $this->assertEquals(
            strtolower($service->getCredentials()->getOrganization()),
            strtolower($currentOrganization->getName())
        );
    }

    public function testGetOrganizationsAsCloudAdmin()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['cloudadmin']);
        $organizations = $service->getOrganizations();
        $this->assertNotEquals(1, count($organizations));
    }

    public function testGetCurrentOrganizationAsCloudAdmin()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['cloudadmin']);

        $organizations = $service->getOrganizations();
        $currentOrganization = $service->getCurrentOrganization();
        $this->assertEquals(
            strtolower($service->getCredentials()->getOrganization()),
            strtolower($currentOrganization->getName())
        );
    }

    /*
    public function testDeleteOrganization()
    {
        $service = new Service($this->config['host']);
        $service->login($this->config['orgadmin']);

        $currentOrganization = $service->getCurrentOrganization();
        $this->assertEquals(
            strtolower($service->getCredentials()->getOrganization()),
            strtolower($currentOrganization->getName())
        );
        $currentOrganization->delete();
    }
    */
}
