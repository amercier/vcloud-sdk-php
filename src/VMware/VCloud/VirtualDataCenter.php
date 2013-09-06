<?php

namespace VMware\VCloud;

class VirtualDataCenter extends Entity
{
    protected $vApps = null;
    protected $vAppTemplates = null;
    protected $medias = null;

    public function getOrganization()
    {
        return $this->get('parent');
    }

    public function getVApps()
    {
        return $this->get('vApps', 'retrieveVApps');
    }

    protected function retrieveVApps()
    {
        $vApps = array();
        $vAppRefs = $this->getImplementation()->getVAppRefs();
        if ($vAppRefs !== null) {
            foreach ($vAppRefs as $vAppRef) {
                array_push($vApps, new VApp($this, null, $vAppRef));
            }
        }
        return $vApps;
    }

    public function getVAppById($id, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'vApps',
            'id',
            $id,
            'vApp',
            'Virtual Datacenter ' . $this->getName(),
            $exceptionIfNotFound
        );
    }

    public function getVAppByName($name, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'vApps',
            'name',
            $name,
            'vApp',
            'Virtual Datacenter ' . $this->getName(),
            $exceptionIfNotFound
        );
    }

    public function getVAppTemplates()
    {
        return $this->get('vAppTemplates', 'retrieveVAppTemplates');
    }

    protected function retrieveVAppTemplates()
    {
        $vAppTemplates = array();
        $vAppTemplateRefs = $this->getImplementation()->getVAppTemplateRefs();
        if ($vAppTemplateRefs !== null) {
            foreach ($vAppTemplateRefs as $vAppTemplateRef) {
                array_push($vAppTemplates, new VAppTemplate($this, null, $vAppTemplateRef));
            }
        }
        return $vAppTemplates;
    }

    public function getVAppTemplateByName($name, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'vAppTemplates',
            'name',
            $name,
            'vApp Template',
            'Virtual Datacenter ' . $this->getName(),
            $exceptionIfNotFound
        );
    }

    public function getVAppTemplateById($id, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'vAppTemplates',
            'id',
            $id,
            'vApp Template',
            'Virtual Datacenter ' . $this->getName(),
            $exceptionIfNotFound
        );
    }

    public function getMedias()
    {
        return $this->get('medias', 'retrieveMedias');
    }

    protected function retrieveMedias()
    {
        $medias = array();
        $mediaRefs = $this->getImplementation()->getMediaRefs();
        if ($mediaRefs !== null) {
            foreach ($mediaRefs as $mediaRef) {
                array_push($medias, new Media($this, null, $mediaRef));
            }
        }
        return $medias;
    }

    public function getMediaByName($name, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'medias',
            'name',
            $name,
            'media',
            'Virtual Datacenter ' . $this->getName(),
            $exceptionIfNotFound
        );
    }

    public function getMediaById($id, $exceptionIfNotFound = true)
    {
        return $this->getBy(
            'medias',
            'id',
            $id,
            'media',
            'Virtual Datacenter ' . $this->getName(),
            $exceptionIfNotFound
        );
    }
}
