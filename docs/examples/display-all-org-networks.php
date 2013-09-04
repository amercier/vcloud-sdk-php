#!/usr/bin/env php
<?php

require_once dirname(__FILE__) . '/config/bootstrap.php';

$service = new VMware\VCloud\Service($config['host']);

Cli\Helpers\Job::run('Authenticating against ' . $config['host'], function() {
    global $service, $config;
    $service->login($config['cloudadmin']);
});

if ($service->isLoggedIn()) {
    echo "\n";
    foreach ($service->getOrganizations() as $org) {
        $networks = $org->getNetworks();
        if (count($networks) > 0) {

            echo "--------------------------------------------------------------------------------\n";
            echo " Organization " . strtoupper($org->getName()) . "\n";
            echo "--------------------------------------------------------------------------------\n";

            $maxNameLength = max(array_map(function($network) {
                return strlen('' . $network);
            }, $networks));
            foreach ($networks as $network) {
                echo " ▸ " . str_pad($network, $maxNameLength) . "   ";
                echo str_pad($network->getIpPool()->getSubnet(), 18) . "   ";
                echo str_pad($network->getGateway(), 15) . "   ";
                echo $network->getFenceMode() . ' ';
                try {
                    echo ($network->getParentNetwork() ? 'to ' . $network->getParentNetwork() : '');
                } catch (VMware\VCloud\Exception\ObjectNotFound $e) {
                    echo 'to ??? (' . $e->getMessage() . ')';
                }
                echo  "\n";
            }
            echo "\n";
        }
    }
}
