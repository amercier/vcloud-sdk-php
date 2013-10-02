<?php

namespace VMware\VCloud;

use VMware\VCloud\Http\Host;

class Service extends Object
{
    const QUERY_PAGE_SIZE = 128;

    protected $implementation;
    protected $host;
    protected $httpConfiguration;
    protected $credentials;
    protected $loggedIn = false;
    protected $organizations = array();
    protected $externalNetworks;
    protected $virtualMachines;

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
            strtolower($name),
            'Organization',
            'vCloud Director ' . $this->getHost(),
            $exceptionIfNotFound
        );
    }

    public function getCurrentOrganization()
    {
        return $this->getOrganizationByName($this->getCredentials()->getOrganization());
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

    protected function queryRecords($type)
    {
        $records = array();

        for ($page = 1, $continue = true; $continue; $page++) {

            $params = new \VMware_VCloud_SDK_Query_Params();
            $params->setPageSize(self::QUERY_PAGE_SIZE);
            $params->setPage($page);

            $queryService = $this->getImplementation()->getQueryService();
            $result = $queryService->queryRecords($type, $params);

            $records = array_merge($records, $result->getRecord());

            $continue = in_array(
                'lastPage',
                array_map(
                    function ($link) {
                        return $link->get_rel();
                    },
                    $result->getLink()
                )
            );
        }

        return $records;
    }

    protected function queryReferences($type)
    {
        $references = array();

        for ($page = 1, $continue = true; $continue; $page++) {

            $params = new \VMware_VCloud_SDK_Query_Params();
            $params->setPageSize(self::QUERY_PAGE_SIZE);
            $params->setPage($page);

            $queryService = $this->getImplementation()->getQueryService();
            $result = $queryService->queryReferences($type, $params);

            $references = array_merge($references, $result->getReference());

            $continue = in_array(
                'lastPage',
                array_map(
                    function ($link) {
                        return $link->get_rel();
                    },
                    $result->getLink()
                )
            );
        }

        return $references;
    }

    public function getAllVirtualMachines()
    {
        return $this->get('virtualMachines', 'retrieveAllVirtualMachines');
    }

    protected function retrieveAllVirtualMachines()
    {
        $virtualMachines = array();
        foreach ($this->queryRecords(\VMware_VCloud_SDK_Query_Types::ADMIN_VM) as $record) {
            if ($record->get_isVAppTemplate() !== "1") {
                array_push($virtualMachines, VirtualMachine::factory($record, $this));
            }
        }
        return $virtualMachines;
    }

    public function getVirtualMachineById($id, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'allVirtualMachines',
            'id',
            $id,
            'Virtual Machine',
            'vCloud Director ' . $this->getHost(),
            $exceptionIfNotFound
        );
    }

    public function getAllOrganizations()
    {
        // $Organizations = array();
        // foreach ($this->queryRecords(\VMware_VCloud_SDK_Query_Types::ORGANIZATION) as $record) {
        //     array_push($Organizations, Organization::factory($record, $this));
        // }
        // return $Organizations;
        return $this->getOrganizations();
    }

    public function getAllVirtualDataCenters()
    {
        $virtualDataCenters = array();
        foreach ($this->queryRecords(\VMware_VCloud_SDK_Query_Types::ADMIN_ORG_VDC) as $record) {
            array_push($virtualDataCenters, VirtualDataCenter::factory($record, $this));
        }
        return $virtualDataCenters;
    }

    public function getAllExternalNetworks()
    {
        $externalNetworks = array();
        foreach ($this->queryRecords(\VMware_VCloud_SDK_Query_Types::EXTERNAL_NETWORK) as $record) {
            array_push($externalNetworks, ExternalNetwork::factory($record, $this));
        }
        return $externalNetworks;
    }

    public function getAllVApps()
    {
        $vApps = array();
        foreach ($this->queryRecords(\VMware_VCloud_SDK_Query_Types::ADMIN_VAPP) as $record) {
            array_push($vApps, VApp::factory($record, $this));
        }
        return $vApps;
    }

    public function getAllVAppTemplates()
    {
        $vAppTemplates = array();
        foreach ($this->queryRecords(\VMware_VCloud_SDK_Query_Types::ADMIN_VAPP_TEMPLATE) as $record) {
            array_push($vAppTemplates, VAppTemplate::factory($record, $this));
        }
        return $vAppTemplates;
    }

    public function getAllOrganizationNetworks()
    {
        $organizationNetworks = array();
        foreach ($this->queryRecords(\VMware_VCloud_SDK_Query_Types::ADMIN_ORG_NETWORK) as $record) {
            array_push($organizationNetworks, OrganizationNetwork::factory($record, $this));
        }
        return $organizationNetworks;
    }


    public function getAllCatalogs()
    {
        $catalogs = array();
        foreach ($this->queryRecords(\VMware_VCloud_SDK_Query_Types::ADMIN_CATALOG) as $record) {
            array_push($catalogs, Catalog::factory($record, $this));
        }
        return $catalogs;
    }
}
