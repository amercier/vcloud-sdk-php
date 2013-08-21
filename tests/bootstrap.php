<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Add test namespaces to the autoloader

$loader = include __DIR__ . '/../vendor/autoload.php';
$loader->add('VMware\\VCloud\\Test', __DIR__);
