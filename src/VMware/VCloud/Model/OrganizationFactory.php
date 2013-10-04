<?php

namespace VMware\VCloud\Model;

class OrganizationFactory
{

    public function fromOrg(\VMware_VCloud_API_OrgType $org)
    {
        return array_merge(
            $this->fromResource($resource),
            $this->fromApiObject(
                array(),
                array(
                    'FullName' => null
                    'IsEnabled' => null
                )
            )
        );
    }

    public function fromAdminOrg(\VMware_VCloud_API_AdminOrgType $adminOrg)
    {
        return array_merge(
            $this->fromOrg($adminOrg),
            $this->fromApiObject(
                array(),
                array(),
                array(
                    'Catalogs' => $this->fromReference,
                    'Groups' => $this->fromReference,
                    'Catalogs' => $this->fromReference,
                    'Networks' => $this->fromReference,
                    'Settings' => $this->fromReference,
                    'Users' => $this->fromReference,
                    'Vdcs' => $this->fromReference,
                )
            )
        );
    }
}
