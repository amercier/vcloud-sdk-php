#!/usr/bin/env php
<?php

require_once dirname(__FILE__) . '/config/bootstrap.php';

$service = new VMware\VCloud\Service($config['host']);

Cli\Helpers\Job::run('Authenticating against ' . $config['host'], function() {
    global $service, $config;
    $service->login($config['orgadmin']);
});

if ($service->isLoggedIn()) {

    $vApp = null;

    Cli\Helpers\Job::run('Looking for vApp "' . $config['vapp'] . '"', function() {
        global $service, $config, $vApp;
        $vApp = $service->getCurrentOrganization()->getVAppByName($config['vapp']);
    });

    if ($vApp !== null) {
        echo "\n";

        echo "--------------------------------------------------------------\n";
        echo " vApp " . strtoupper($vApp->getName()) . "\n";
        echo "--------------------------------------------------------------\n";

        echo " ▸ owner           \t\t" ;
        echo $vApp->getOwner()->getName() . ' ';
        echo '(' . $vApp->getOwner()->getFullName() . ")\n";

        echo " ▸ virtual machines\t\t" ;
        echo ($vApp->getVirtualMachines() ? '' : 'none') . "\n";
        foreach($vApp->getVirtualMachines() as $virtualMachine) {
            echo "      ▸ " . $virtualMachine . "\n";
        }

        echo " ▸ vApp networks   \t\t" ;
        echo ($vApp->getNetworks() ? '' : 'none') . "\n";
        foreach($vApp->getNetworks() as $network) {
            echo "      ▸ " . $network . " ";
            echo "\n";
        }

        echo "\n";
    }
}
