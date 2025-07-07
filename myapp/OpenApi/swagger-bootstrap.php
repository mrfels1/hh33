<?php

require __DIR__ . '/../vendor/autoload.php';

foreach (glob(__DIR__ . '/../controllers/*.php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/../models/*.php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/MockSchemas/*.php') as $filename) {
    require_once $filename;
}

require_once __DIR__ . '/OpenApiSpec.php';
