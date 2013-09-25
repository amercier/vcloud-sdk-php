<?php

namespace VMware\VCloud;

/**
 * @todo toLink?
 */
abstract class IdentifiableResource extends Resource
{
    const ID_PATTERN = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';

    protected $reference = null;

    public function __construct(
        Object $parent,
        \VMware_VCloud_API_IdentifiableResourceType $model = null,
        \VMware_VCloud_API_ReferenceType $reference = null
    ) {
        parent::__construct($parent, $model);
        $this->set('reference', $reference);
    }

    public static function getIdFromHref($href)
    {
        return preg_replace('/.*(' . self::ID_PATTERN . ').*/', '$1', $href);
    }

    public function getId()
    {
        return self::getIdFromHref($this->getReferenceOrModel()->get_href());
    }

    public function getHref()
    {
        return $this->getReferenceOrModel()->get_href();
    }

    public function getType()
    {
        return $this->getReferenceOrModel()->get_type();
    }

    protected function getReference()
    {
        return $this->get('reference');
    }

    protected function getReferenceOrModel()
    {
        return $this->get('reference') === null ? $this->get('model') : $this->get('reference');
    }

    public function __toString()
    {
        return '<'
            . preg_replace('/application\\/vnd.vmware.vcloud.(.*)\\+xml/', '$1', $this->getType())
            . '> ' . $this->getId();
    }
}
