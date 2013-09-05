<?php

namespace VMware\VCloud;

class Catalog extends Entity
{
    const TYPE_VAPP_TEMPLATE = 'application/vnd.vmware.vcloud.vAppTemplate+xml';
    const TYPE_MEDIA = 'application/vnd.vmware.vcloud.media+xml';

    protected $catalogItems = null;

    public function getVAppTemplates()
    {
        $catalogItems = $this->get('catalogItems', 'retrieveCatalogItems');
        return $catalogItems[self::TYPE_VAPP_TEMPLATE];
    }

    protected function retrieveCatalogItems()
    {
        $catalogItems = array(
            self::TYPE_VAPP_TEMPLATE => array(),
            self::TYPE_MEDIA => array(),
        );

        foreach ($this->getImplementation()->getCatalogItems() as $catalogItem) {
            $entity = $catalogItem->getEntity();
            array_push(
                $catalogItems[$entity->get_type()],
                $this->createCatalogItemInstance($entity)
            );
        }
        return $catalogItems;
    }

    protected function createCatalogItemInstance(\VMware_VCloud_API_ReferenceType $entity)
    {
        $type = $entity->get_type();
        switch($type) {
            case self::TYPE_VAPP_TEMPLATE:
                return new VAppTemplate($this, null, $entity);
            case self::TYPE_MEDIA:
                return new Media($this, null, $entity);
            default:
                throw new \RuntimeException('Unknown catalog item type ' . $type);
        }
    }
}
