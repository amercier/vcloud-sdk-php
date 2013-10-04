<?php

namespace VMware\VCloud\Model;

abstract class AbstractFactory
{
    protected function getClassName()
    {
        return $this->className;
    }

    protected static function map($elements, $callback)
    {
        if ($elements === null) {
            return array();
        }

        $result = array();
        foreach ($elements as $element) {
            array_push($result, $callback ? $callback($element) : $element);
        }
        return $result;
    }

    protected function fromApiObject(
        \VMware_VCloud_API_VCloudExtensibleType $object,
        $attributes,
        $elements = array(),
        $lists = array()
    ) {
        $result = array();

        // Attributes
        foreach ($attributes as $key => $callback) {
            $getter = 'get_' . $key;
            $result[$key] = $callback ? $callback($resource->$getter()) : $resource->$getter();
        }

        // Elements
        foreach ($elements as $key => $callback) {
            $getter = 'get' . $key;
            $result[$key] = $callback ? $callback($resource->$getter()) : $resource->$getter();
        }

        // Lists
        foreach ($list as $key => $callback) {
            $getter = 'get' . $key;
            $result[$key] = self::map($resource->$getter(), $callback);
        }
    }

    protected function fromReference(\VMware_VCloud_API_ReferenceType $reference)
    {
        return $this->fromApiObject(
            array(
                'href' => null,
                'id' => null,
                'name' => null,
                'type' => null,
            )
        );
    }

    protected function fromLink()
    {
        return array_merge(
            $this->fromResource($resource),
            $this->fromApiObject(array('rel' => null))
        );
    }

    protected function fromResource(\VMware_VCloud_API_ResourceType $resource)
    {
        return $this->fromApiObject(
            array(
                'href' => null,
                'type' => null,
            ),
            array(),
            array(
                'Link' => $this->fromLink
            )
        );
    }

    protected function fromIdentifiableResource(\VMware_VCloud_API_IdentifiableResourceType $resource)
    {
        return array_merge(
            $this->fromResource($resource),
            $this->fromApiObject(
                array('id' => null)
            )
        );
    }

    protected function fromEntity(\VMware_VCloud_API_EntityType $entity)
    {
        return array_merge(
            $this->fromIdentifiableResource($entity),
            $this->fromApiObject(
                array(
                    'name' => null
                ),
                array(),
                array(
                    'Tasks' => $this->fromTask
                )
            )
        );
    }
}
