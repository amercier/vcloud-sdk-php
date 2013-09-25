<?php

namespace VMware\VCloud;

class Organization extends Entity
{
    protected $networks = null;
    protected $virtualDataCenters = null;
    protected $catalogs = null;

    public function __construct(
        Service $parent,
        \VMware_VCloud_API_OrgType $model = null,
        \VMware_VCloud_API_ReferenceType $reference = null
    ) {
        parent::__construct($parent, $model, $reference);
    }

    protected function getParent()
    {
        return $this->getService();
    }

    protected function retrieveParent()
    {
        // never called as we override ::getParent()
    }

    protected function getImplementationGetterName()
    {
        return 'getOrg';
    }

    public function getService()
    {
        return $this->get('parent');
    }

    public function getFullName()
    {
        return $this->getModel()->getFullName();
    }

    public function getNetworks()
    {
        return $this->get('networks', 'retrieveNetworks');
    }

    protected function retrieveNetworks()
    {
        $networks = array();
        foreach ($this->getImplementation()->getOrgNetworkRefs() as $orgNetworkRef) {
            array_push($networks, new OrganizationNetwork($this, null, $orgNetworkRef));
        }
        return $networks;
    }

    public function getNetworkById($id, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'networks',
            'id',
            $id,
            'Organization Network',
            'Organization ' . $this->getName(),
            $exceptionIfNotFound
        );
    }

    public function getVirtualDataCenters()
    {
        return $this->get('virtualDataCenters', 'retrieveVirtualDataCenters');
    }

    protected function retrieveVirtualDataCenters()
    {
        $virtualDataCenters = array();
        foreach ($this->getImplementation()->getVdcRefs() as $vdcRef) {
            array_push($virtualDataCenters, new VirtualDataCenter($this, null, $vdcRef));
        }
        return $virtualDataCenters;
    }

    public function getVAppByName($name, $exceptionIfNotFound = true)
    {
        foreach ($this->getVirtualDataCenters() as $virtualDataCenter) {
            $vApp = $virtualDataCenter->getVAppByName($name, false);
            if ($vApp !== false) {
                return $vApp;
            }
        }
        if ($exceptionIfNotFound) {
            throw new Exception\ObjectNotFound('vApp', 'name', $name, 'Virtual Datacenter ' . $this->getName());
        } else {
            return false;
        }
    }

    public function getCatalogs()
    {
        return $this->get('catalogs', 'retrieveCatalogs');
    }

    protected function getCatalogOrgLink(\VMware_VCloud_API_CatalogType $catalog)
    {
        foreach ($catalog->getLink() as $link) {
            if ($link->get_type() === 'application/vnd.vmware.vcloud.org+xml') {
                return $link;
            }
        }
        return $link;
    }

    protected function retrieveCatalogs()
    {
        $catalogs = array();
        foreach ($this->getImplementation()->getCatalogs() as $catalog) {
            $link = $this->getCatalogOrgLink($catalog);
            $organization = $link === null ? null : $this->getService()->getOrganizationById(
                IdentifiableResource::getIdFromHref($link->get_href())
            );
            array_push($catalogs, new Catalog($organization, $catalog));
        }
        return $catalogs;
    }

    public function getCatalogById($id, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'catalogs',
            'id',
            $id,
            'Catalog',
            'Organization ' . $this->getName(),
            $exceptionIfNotFound
        );
    }

    public function getVAppTemplateById($id, $exceptionIfNotFound = true)
    {
        foreach ($this->getVirtualDataCenters() as $virtualDataCenter) {
            $vAppTemplate = $virtualDataCenter->getVAppTemplateById($id, false);
            if ($vAppTemplate !== false) {
                return $vAppTemplate;
            }
        }
        if ($exceptionIfNotFound) {
            throw new Exception\ObjectNotFound('vApp Template', 'id', $id, 'Virtual Datacenter ' . $this->getName());
        } else {
            return false;
        }
    }

    public function getMediaById($id, $exceptionIfNotFound = true)
    {
        foreach ($this->getVirtualDataCenters() as $virtualDataCenter) {
            $media = $virtualDataCenter->getMediaById($id, false);
            if ($media !== false) {
                return $media;
            }
        }
        if ($exceptionIfNotFound) {
            throw new Exception\ObjectNotFound('media', 'id', $id, 'Virtual Datacenter ' . $this->getName());
        } else {
            return false;
        }
    }
}
