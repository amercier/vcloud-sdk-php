<?php

namespace VMware\VCloud;

use VMware\VCloud\Http\Host;

class Service extends AbstractObject
{
    protected $service = null;
    protected $host = null;
    protected $httpConfiguration = null;
    protected $credentials = null;
    protected $loggedIn = false;

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

    public function getService()
    {
        return $this->get('service', 'createService');
    }

    protected function createService()
    {
        return \VMware_VCloud_SDK_Service::getService();
    }

    public function isLoggedIn()
    {
        return $this->loggedIn;
    }

    public function login($credentials)
    {
        if (is_array($credentials)) {
            $credentials = new Credentials($credentials);
        } elseif (!($credentials instanceof Credentials)) {
            throw new Exception\InvalidParameter($credentials, array('Credentials', 'array'));
        }

        $this->set('credentials', $credentials);

        $orgList = $this->getService()->login(
            $this->get('host')->getUrl(),
            $this->get('credentials')->toArray(),
            $this->get('httpConfiguration')->toArray()
        );

        $this->set('loggedIn', true);
        // FIXME $this->setOrganizationList($orgList);

        return $this;
    }

    public function logout()
    {
        if (!$this->isLoggedIn()) {
            throw new Exception\AlreadyLoggedOut($this->host);
        }
        $this->service->logout();
        $this->set('loggedIn', false);
    }
}
