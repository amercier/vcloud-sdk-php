<?php
namespace VMware\VCloud;

/**
 * A Model Object is an object that is related to a vCloud Resource. Therefore
 * it contains a reference to the Service object that it has been created from.
 */
abstract class ModelObject extends Object
{

    const PATTERN_HREF_TO_ID = '/.*([^\/]*)/';

    /**
     * The reference to the Service object
     * @var Service
     */
    // protected $service = null;

    /**
     * The reference to the Service object
     * @var Service
     */
    protected $reference = null;

    /**
     * The reference to the Service object
     * @var Service
     */
    protected $model = null;

    protected $parent;

    /**
     * Create a new Model Object.
     */
    public function __construct(Object $parent, $modelReferenceOrObject)
    {
        $this->set('parent', $parent);
        if ($modelReferenceOrObject instanceof \VMware_VCloud_SDK_Abstract) {
            $this->set('model', $modelReferenceOrObject);
        } elseif ($modelReferenceOrObject instanceof \VMware_VCloud_API_ReferenceType) {
            $this->set('reference', $modelReferenceOrObject);
        } else {
            throw new Exception\InvalidParameter(
                $modelReferenceOrObject,
                array('VMware_VCloud_SDK_Abstract', 'VMware_VCloud_API_ReferenceType')
            );
        }
    }

    /**
     * Get the Service object
     * @return Service Returns the Service object
     */
    abstract protected function getService();

    /**
     * Get the Href
     * @return Service Returns the Href
     */
    public function getHref()
    {
        return $this->get('reference')->getHref();
    }

    public function getId()
    {
        return preg_replace(self::PATTERN_HREF_TO_ID, '$1', $this->getHref());
    }

    public function getName()
    {
        return $this->getReference()->get_name();
    }

    protected function getReference()
    {
        return $this->get('reference', 'createReference');
    }

    protected function createReference()
    {
        return $this->get('model')->getRef();
    }

    protected function getModel()
    {
        return $this->get('model', 'createModel');
    }

    protected function createModel()
    {
        return $this->getService()->createModelFromReference($this->get('reference'));
    }

    protected function getModelData()
    {
        return $this->getModel()->getDataObj();
    }
}
