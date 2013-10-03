<?php

namespace VMware\VCloud;

use VMware\VCloud\Http\Host;

class Service extends Object
{
    const QUERY_PAGE_SIZE = 128;

    protected $sdkObject;
    protected $host;
    protected $httpConfiguration;
    protected $credentials;
    protected $loggedIn;
    protected $organizations;
    protected $externalNetworks;

    protected $dataObjectsRegistry;
    protected $sdkObjectsRegistry;

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
        $this->init();
    }

    protected function init()
    {
        $this->$loggedIn = false;
        $this->$organizations = array();
        $this->dataObjectsRegistry = new Map();
        $this->sdkObjectsRegistry = new Map();
    }

    protected function getSdkObject()
    {
        return $this->get('sdkObject', 'createSdkObject');
    }

    protected function createSdkObject()
    {
        return \VMware_VCloud_SDK_Service::getService();
    }

    public function hasDataObject($href) {
        return array_key_exists($href, $this->dataObjectsRegistry);
    }

    public function getDataObject($href, $exceptionIfNotFound = true)
    {
        return $this->getByKey(
            'dataObjectsRegistry',
            $href,
            $exceptionIfNotFound,
            'Data object with href $value does not exist in registry'
        );
    }

    public function addDataObject(\VMware_VCloud_API_IdentifiableResourceType $dataObject)
    {
        $href = $dataObject->get_href();
        if (!$this->hasDataObject($href)) {
            $this->dataObjectsRegistry->set($href, $dataObject);
        }
        return $this;
    }

    public function hasSdkObject($href) {
        return array_key_exists($href, $this->sdkObjectsRegistry);
    }

    public function getSdkObject($href, $exceptionIfNotFound = true)
    {
        return $this->getByKey(
            'sdkObjectsRegistry',
            $href,
            $exceptionIfNotFound,
            'SDK object with href $value does not exist in registry'
        );
    }

    public function addSdkObject(\VMware_VCloud_API_IdentifiableResourceType $sdkObject)
    {
        $href = $sdkObject->get_href();
        if (!$this->hasDataObject($href)) {
            $this->sdkObjectsRegistry->set($href, $sdkObject);
        }
        return $this;
    }

    public function getHost()
    {
        return $this->get('host');
    }

    public function getCredentials()
    {
        return $this->get('credentials');
    }

    public function getOrganizations()
    {
        return $this->get('organizations');
    }

    public function isLoggedIn()
    {
        return $this->loggedIn;
    }

    public function login($credentials, $apiVersion = '5.1')
    {
        if (!($credentials instanceof Credentials)) {
            $credentials = new Credentials($credentials);
        }

        $this->set('credentials', $credentials);

        if ($apiVersion === '1.0') {
            $this->getImplementation()->setLoginUrl($this->get('host')->getUrl() . '/api/v1.0/login');
        } elseif ($apiVersion === '1.5') {
            $this->getImplementation()->setLoginUrl($this->get('host')->getUrl() . '/api/sessions');
        }

        $orgList = $this->getImplementation()->login(
            $this->get('host')->getUrl(),
            $this->get('credentials')->toArray(),
            $this->get('httpConfiguration')->toArray(),
            $apiVersion
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

    public function getAuthenticationToken()
    {
        return $this->getImplementation()->getToken();
    }

