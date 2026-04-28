<?php

// Bootstrap for running package tests via the root project's vendor
// __DIR__ = packages/taba/crm/tests — root is 4 levels up
$rootAutoload = __DIR__ . '/../../../../vendor/autoload.php';

if (file_exists($rootAutoload)) {
    require $rootAutoload;
} else {
    // Fallback: package-level vendor (composer install inside package)
    require __DIR__ . '/../vendor/autoload.php';
}
