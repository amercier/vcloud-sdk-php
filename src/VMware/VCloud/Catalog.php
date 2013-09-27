<?php

namespace VMware\VCloud;

class Catalog extends Entity
{
    const TYPE_VAPP_TEMPLATE = 'application/vnd.vmware.vcloud.vAppTemplate+xml';
    const TYPE_MEDIA = 'application/vnd.vmware.vcloud.media+xml';

    protected $catalogItems = null;

    protected function retrieveParent()
    {
        throw new \Exception('Not implemented');
    }

    public function getOrganization()
    {
        return $this->getParent();
    }

    public function getVAppTemplates()
    {
        $catalogItems = $this->get('catalogItems', 'retrieveCatalogItems');
        return $catalogItems[self::TYPE_VAPP_TEMPLATE];
    }

    public function getMedias()
    {
        $catalogItems = $this->get('catalogItems', 'retrieveCatalogItems');
        return $catalogItems[self::TYPE_MEDIA];
    }

    protected function retrieveCatalogItems()
    {
        $catalogItems = array(
            self::TYPE_VAPP_TEMPLATE => array(),
            self::TYPE_MEDIA => array(),
        );

        foreach ($this->getImplementation()->getCatalogItems() as $catalogItem) {
            $entity = $catalogItem->getEntity();
            $instance = $this->createCatalogItemInstance($entity);
            if ($instance !== false) {
                array_push(
                    $catalogItems[$entity->get_type()],
                    $instance
                );
            }
        }
        return $catalogItems;
    }

    protected function createCatalogItemInstance(\VMware_VCloud_API_ReferenceType $entity)
    {
        $type = $entity->get_type();
        switch($type) {
            case self::TYPE_VAPP_TEMPLATE:
                return $this->getOrganization()->getVAppTemplateById(
                    IdentifiableResource::getIdFromHref($entity->get_href()),
                    false
                );
            case self::TYPE_MEDIA:
                return $this->getOrganization()->getMediaById(
                    IdentifiableResource::getIdFromHref($entity->get_href()),
                    false
                );
            default:
                throw new \RuntimeException('Unknown catalog item type ' . $type);
        }
    }

    public static function factory(\VMware_VCloud_API_QueryResultAdminCatalogRecordType $record, Object $parent)
    {
        $ref = new \VMware_VCloud_API_ReferenceType();
        $ref->set_href($record->get_href());
        $ref->set_id('urn:vcloud:catalog:' . IdentifiableResource::getIdFromHref($record->get_href()));
        $ref->set_name($record->get_name());
        $ref->set_type('application/vnd.vmware.vcloud.catalog+xml');

        $organization = new self($parent, null, $ref);

        return $organization;
    }
}
