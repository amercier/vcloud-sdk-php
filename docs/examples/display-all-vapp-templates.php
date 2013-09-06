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
        foreach ($org->getVirtualDatacenters() as $vdc) {
            echo "--------------------------------------------------------------------------------\n";
            echo " Virtual Datacenter " . strtoupper($vdc) . "\n";
            echo "--------------------------------------------------------------------------------\n";

            // vApp Templates

            $vAppTemplates = $vdc->getVAppTemplates();
            if (count($vAppTemplates) === 0) {
                echo " ▸ 0 vApp Templates\n";
            }
            else {
                echo " ▸ vApp Templates\n";
                $maxNameLength = max(array_map(function($vAppTemplate) {
                    return strlen('' . $vAppTemplate);
                }, $vAppTemplates));
                foreach($vdc->getVAppTemplates() as $vAppTemplate) {
                    echo "     ▸ " . str_pad($vAppTemplate, $maxNameLength) . " ";
                    try {
                        echo "   " . str_pad(count($vAppTemplate->getVirtualMachines()), 2, ' ', STR_PAD_LEFT) . " VMs";
                        echo "   " . $vAppTemplate->getCatalog() . "\n";
                    }
                    catch (VMware_VCloud_SDK_Exception $e) {
                        echo "(### ERROR ###)\n";
                    }
                }
            }

            // Medias

            $medias = $vdc->getMedias();
            if (count($medias) === 0) {
                echo " ▸ 0 medias\n";
            }
            else {
                echo " ▸ medias\n";
                $maxNameLength = max(array_map(function($media) {
                    return strlen('' . $media);
                }, $medias));
                foreach($vdc->getMedias() as $media) {
                    echo "    ▸ " . str_pad($media, $maxNameLength) . " ";
                    echo "   " . str_pad($media->getImageType(), 3);
                    echo "   " . count($media->getFiles()) . " files";
                    echo "   " . str_pad($media->getSize(), 10, ' ', STR_PAD_LEFT) . " B\n";
                }
            }
        }
        echo "\n\n";
    }
}
