<?php

namespace VMware\VCloud;

/**
 * @todo toLink?
 */
class IdentifiableResource extends Resource
{
    const ID_PATTERN = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';

    public function __construct(
        Object $parent,
        \VMware_VCloud_API_ReferenceType $reference = null,
        \VMware_VCloud_API_IdentifiableResourceType $model = null
    ) {
        parent::__construct($parent, $reference, $model);
    }

    public function getId()
    {
        return preg_replace('/.*(' . self::ID_PATTERN . ').*/', '$1', $this->getReferenceOrModel()->get_href());
    }

    public function getHref()
    {
        return $this->getReferenceOrModel()->get_href();
    }

    public function getType()
    {
        return $this->getReferenceOrModel()->get_type();
    }

    public function __toString()
    {
        return '<'
            . preg_replace('/application\\/vnd.vmware.vcloud.(.*)\\+xml/', '$1', $this->getType())
            . '> ' . $this->getId();
    }
}
