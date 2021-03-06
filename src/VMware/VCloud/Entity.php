<?php

namespace VMware\VCloud;

/**
 * @todo getTasks?
 */
abstract class Entity extends IdentifiableResource
{
    protected $implementation;
    protected $metadata;

    public function __construct(
        Object $parent,
        \VMware_VCloud_API_EntityType $model = null,
        \VMware_VCloud_API_ReferenceType $reference = null,
        \VMware_VCloud_SDK_Abstract $implementation = null
    ) {
        parent::__construct($parent, $model, $reference);
        $this->set('implementation', $implementation);
    }

    public function getImplementation()
    {
        return $this->get('implementation', 'createImplementation');
    }

    protected function createImplementation()
    {
        if ($this->getReference() !== null) {
            return $this->getService()->createImplementationFromReference($this->getReference());
        } else {
            return $this->getService()->createImplementationFromEntity($this->getModel());
        }
    }

    public function getModel()
    {
        return $this->get('model', 'createModelFromImplementation');
    }

    protected function getReferenceOrModel()
    {
        return $this->get('implementation') === null ? parent::getReferenceOrModel() : $this->getModel();
    }

    protected function createModelFromImplementation()
    {
        $methodName = $this->getImplementationGetterName();
        return $this->getImplementation()->$methodName();
    }

    protected function getImplementationGetterName()
    {
        return 'get' . join('', array_slice(explode('\\', get_class($this)), -1));
    }

    public function getName()
    {
        return $this->getReferenceOrModel()->get_name();
    }

    public function getDescription()
    {
        return $this->getModel()->getDescription();
    }

    public function getMetadata()
    {
        return $this->get('metadata', 'retrieveMetadata');
    }

    protected function retrieveMetadata()
    {
        return new Metadata($this, $this->getImplementation()->getMetadata(), null);
    }

    protected function getLinkByRel($rel)
    {
        $links = $this->getModel()->getLink();
        if ($links !== null) {
            foreach ($links as $link) {
                if ($link->get_rel() === $rel) {
                    return $link;
                }
            }
        }
        return null;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
