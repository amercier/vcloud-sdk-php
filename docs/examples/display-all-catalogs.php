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
            $vAppTemplates = $catalog->getVAppTemplates();
            if (count($vAppTemplates) === 0) {
                echo " ▸ 0 vApp Templates\n";
            }
            else {
                $maxNameLength = max(array_map(function($vAppTemplate) {
                    return strlen('' . $vAppTemplate);
                }, $vAppTemplates));
                foreach($vAppTemplates as $vAppTemplate) {
                    echo "    ▸ " . str_pad($vAppTemplate, $maxNameLength) . "\n";
                    $virtualMachines = $vAppTemplate->getVirtualMachines();
                    if (count($virtualMachines) === 0) {
                        echo "       ▸ 0 virtual machines\n";
                    }
                    else {
                        $maxVMNameLength = max(array_map(function($virtualMachine) {
                            return strlen('' . $virtualMachine);
                        }, $virtualMachines));
                        foreach ($virtualMachines as $virtualMachine) {
                            try {
                                echo "       ▸ " . str_pad($virtualMachine, $maxVMNameLength) . "\n";
                                // echo $virtualMachine->getHref() . "\n";
                                // echo str_pad($virtualMachine->getVirtualCpu()->getQuantity(), 2, ' ', STR_PAD_LEFT) . " vCPU" . ($virtualMachine->getVirtualCpu()->getQuantity() === 1 ? ' ' : 's') . " / ";
                                // echo str_pad($virtualMachine->getVirtualMemory()->getQuantity(), 5, ' ', STR_PAD_LEFT) . " MB\n";
                            }
                            catch (VMware_VCloud_SDK_Exception $e) {
                                echo "(### ERROR ###)\n";
                            }
                        }
                    }
                }
            }
            $medias = $catalog->getMedias();
            if (count($medias) === 0) {
                echo " ▸ 0 medias\n";
            }
            else {
                echo " ▸ medias\n";
                $maxNameLength = max(array_map(function($media) {
                    return strlen('' . $media);
                }, $medias));
                foreach($medias as $media) {
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
