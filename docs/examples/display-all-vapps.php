#!/usr/bin/env php
<?php

require_once dirname(__FILE__) . '/config/bootstrap.php';

$service = new VMware\VCloud\Service($config['host']);

Cli\Helpers\Job::run('Authenticating against ' . $config['host'], function() {
    global $service, $config;
    $service->login($config['orgadmin']);
});

if ($service->isLoggedIn()) {
    echo "\n";
    foreach ($service->getCurrentOrganization()->getVirtualDatacenters() as $vdc) {
        echo "--------------------------------------------------------------\n";
        echo " Virtual Data Center " . strtoupper($vdc->getName()) . "\n";
        echo "--------------------------------------------------------------\n";
        foreach ($vdc->getVAppTemplates() as $vApp) {
            echo ' ▸ ' . $vApp->getName() . "\n";
        }
        echo "\n";
    }
}
