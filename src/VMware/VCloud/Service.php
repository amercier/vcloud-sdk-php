<?php

namespace VMware\VCloud;

use VMware\VCloud\Http\Host;

class Service extends Object
{
    protected $implementation = null;
    protected $host = null;
    protected $httpConfiguration = null;
    protected $credentials = null;
    protected $loggedIn = false;
    protected $organizations = array();
    protected $externalNetworks = null;

    public function __construct($host, Http\Configuration $httpConfiguration = null)
    {
        if (is_string($host)) {
            $host = new Host($host);
        } elseif (!($host instanceof Host)) {
            throw new Exception\InvalidParameter($host, array('VMware\VCloud\Http\Host', 'string'));
        }

        $this->set('host', $host);
        $this->set(
            'httpConfiguration',
            $httpConfiguration === null
            ? Http\Configuration::getDefaultConfiguration()
            : $httpConfiguration
        );
    }

    public function getHost()
    {
        return $this->get('host');
    }

    public function getImplementation()
    {
        return $this->get('implementation', 'createImplementation');
    }

    protected function createImplementation()
    {
        return \VMware_VCloud_SDK_Service::getService();
    }

    public function isLoggedIn()
    {
        return $this->loggedIn;
    }

    public function login($credentials)
    {
        if (!($credentials instanceof Credentials)) {
            $credentials = new Credentials($credentials);
        }

        $this->set('credentials', $credentials);

        $orgList = $this->getImplementation()->login(
            $this->get('host')->getUrl(),
            $this->get('credentials')->toArray(),
            $this->get('httpConfiguration')->toArray()
        );

        $this->set('loggedIn', true);

        foreach ($orgList->getOrg() as $orgRef) {
            $this->add('organizations', new Organization($this, null, $orgRef));
        }

        return $this;
    }

    public function logout()
    {
        if (!$this->isLoggedIn()) {
            throw new Exception\AlreadyLoggedOut($this->host);
        }
        $this->getImplementation()->logout();
        $this->set('loggedIn', false);
    }

    public function createImplementationFromReference(\VMware_VCloud_API_ReferenceType $reference)
    {
        return $this->getImplementation()->createSdkObj($reference);
    }

    public function createImplementationFromEntity(\VMware_VCloud_API_EntityType $entity)
    {
        return $this->getImplementation()->createSdkObj($entity);
    }

    /*
    public function createModelFromResource(\VMware_VCloud_API_ResourceEntityType $resource)
    {
        return $this->getImplementation()->createSdkObj($resource);
    }
    */

    public function getCredentials()
    {
        return $this->get('credentials');
    }

    public function getOrganizations()
    {
        return $this->get('organizations');
    }

    public function getOrganizationById($id, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'organizations',
            'id',
            $id,
            'Organization',
            'vCloud Director ' . $this->getHost(),
            $exceptionIfNotFound
        );
    }

    public function getOrganizationByName($name, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'organizations',
            'name',
            $name,
            'Organization',
            'vCloud Director ' . $this->getHost(),
            $exceptionIfNotFound
        );
    }

    public function getCurrentOrganization()
    {
        return $this->getOrganizationByName(strtolower($this->getCredentials()->getOrganization()));
    }

    public function getExternalNetworks()
    {
        return $this->get('externalNetworks', 'retrieveExternalNetworks');
    }

    protected function retrieveExternalNetworks()
    {
        $externalNetworks = array();
        foreach ($this->getImplementation()->createSDKAdminObj()->getExternalNetworkRefs() as $externalNetworkRef) {
            array_push($externalNetworks, new ExternalNetwork($this, null, $externalNetworkRef));
        }
        return $externalNetworks;
    }

    public function getExternalNetworkById($id, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'externalNetworks',
            'id',
            $id,
            'External Network',
            'vCloud Director ' . $this->getHost(),
            $exceptionIfNotFound
        );
    }

    public function getExternalNetworkByName($name, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'externalNetworks',
            'name',
            $name,
            'External Network',
            'vCloud Director ' . $this->getHost(),
            $exceptionIfNotFound
        );
    }
}
