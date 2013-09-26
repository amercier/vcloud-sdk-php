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

    $vms =  $service->getAllVirtualMachines();

    foreach ($vms as $vm) {
        if (count($vm->getMetadata()->getEntries()) === 0) {
        }
        else {
            echo " ▸ " . $vm . "\n";
            $entries = $vm->getMetadata()->getEntries();

            $entriesNamesMaxLength = max(array_map(function($entry) {
                return strlen('' . $entry->getName());
            }, $entries));
            $entriesValuesMaxLength = max(array_map(function($entry) {
                return strlen('' . $entry->getValue());
            }, $entries));
            $entriesTypesMaxLength = max(array_map(function($entry) {
                return strlen('' . $entry->getType());
            }, $entries));
            $entriesDomainsMaxLength = max(array_map(function($entry) {
                return strlen('' . $entry->getDomain());
            }, $entries));
            $entriesVisibilitiesMaxLength = max(array_map(function($entry) {
                return strlen('' . $entry->getVisibility());
            }, $entries));

            foreach($entries as $entry) {
                echo "    ▸ " ;
                echo str_pad($entry->getName(), $entriesNamesMaxLength) . "   ";
                echo str_pad($entry->getValue(), $entriesValuesMaxLength) . "   ";
                echo str_pad($entry->getType(), $entriesTypesMaxLength) . "   ";
                echo str_pad($entry->getDomain(), $entriesDomainsMaxLength) . "   ";
                echo str_pad($entry->getVisibility(), $entriesVisibilitiesMaxLength) . "   ";
                echo "\n";
            }
        }
    }
}
