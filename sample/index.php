<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Widi\Components\ServiceLocator\ServiceLocator;

$services = [
    InvokableService::class => [
        'instance' => InvokableService::class,
    ],
    AnotherInvokableService::class => [
        'instance' => InvokableService::class,
        'options' => [
            'factory'   => false,
            'shared'    => false,
            'parameter' => [],
        ],
    ],
    FactoryService::class   => [
        'instance' => ServiceFactory::class,
        'options' => [
            'factory'   => true,
            'parameter' => [],
        ],
    ],
];

$serviceLocator = new ServiceLocator();
$serviceLocator->setArray($services);
