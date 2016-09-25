<?php
use Widi\Components\ServiceLocator\ServiceLocator;

$services = [
    InvokableService::class => [
        'service' => InvokableService::class,
    ],
    AnotherInvokableService::class => [
        'service' => InvokableService::class,
        'options' => [
            'factory'   => false,
            'shared'    => false,
            'parameter' => [],
        ],
    ],
    FactoryService::class   => [
        'service' => ServiceFactory::class,
        'options' => [
            'factory'   => true,
            'parameter' => [],
        ],
    ],
];

$serviceLocator = new ServiceLocator();
$serviceLocator->setArray($services);
