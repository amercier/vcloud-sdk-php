<?php
namespace VMware\VCloud;

/**
 * A Model Object is an object that is related to a vCloud Resource. Therefore
 * it contains a reference to the Service object that it has been created from.
 */
abstract class AbstractModelObject extends AbstractObject
{
    /**
     * The reference to the Service object
     * @var Service
     */
    protected $service = null;

    /**
     * The reference to the Service object
     * @var Service
     */
    protected $href = null;

    /**
     * Get the Service object
     * @return Service Returns the Service object
     */
    public function getService()
    {
        return $this->get('service');
    }

    /**
     * Get the Href
     * @return Service Returns the Href
     */
    public function getHref()
    {
        return $this->get('href');
    }

    /**
     * Create a new Model Object.
     * @param Service $service the Service object
     */
    public function __construct(Service $service, $model = null)
    {
        $this->setService($service);
    }
}
