<?php

namespace VMware\VCloud;

interface Network
{
    public function getName();

    public function getParentNetwork();

    public function getFenceMode();
}
