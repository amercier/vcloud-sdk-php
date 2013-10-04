<?php

namespace VMware\VCloud\Model;

use VMware\VCloud\Object;
use VMware\VCloud\Service;

class Organization extends AbstractModelObject
{
    protected $href;
    protected $type;
    protected $link;
    protected $id;
    protected $name;
    protected $tasks;
    protected $fullName;
    protected $isEnabled;
    protected $catalogs;
    protected $groups;
    protected $catalogs;
    protected $networks;
    protected $settings;
    protected $users;
    protected $virtualDatacenters;

    public function __construct(Service $service)
    {

    }

    public static function fromAdminOrg(\VMware_VCloud_API_AdminOrgType $adminOrg)
    {

    }
