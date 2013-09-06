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
        echo "================================================================================\n";
        echo " Organization " . strtoupper($org) . "\n";
        echo "================================================================================\n";
        echo "\n";
        foreach ($org->getCatalogs() as $catalog) {
            echo "--------------------------------------------------------------------------------\n";
            echo " Catalog " . strtoupper($catalog) . "\n";
            echo "--------------------------------------------------------------------------------\n";
            echo " ▸ vApp Templates\n";
            foreach($catalog->getVAppTemplates() as $vAppTemplate) {
                echo "     ▸ " . $vAppTemplate . " ";
                try {
                    echo "(" . count($vAppTemplate->getVirtualMachines()) . ' vms: ' . implode(', ', $vAppTemplate->getVirtualMachines()) . ")\n";
                }
                catch (VMware_VCloud_SDK_Exception $e) {
                    echo "(### ERROR ###)\n";
                }
            }
            echo " ▸ medias\n";
            foreach($catalog->getMedias() as $media) {
                echo "     ▸ " . $media . " ";
                echo "(" . count($media->getSize()) . " B)\n";
            }
        }
        echo "\n\n";
    }
}
