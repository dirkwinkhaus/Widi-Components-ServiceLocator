<?php

namespace Widi\Components\Test\ServiceLocator;

use Widi\Components\ServiceLocator\ServiceLocatorInterface;

require_once (__DIR__ . '/Service.php');

/**
 * Class FactoryParameter
 *
 * @package Widi\Components\Test\ServiceLocator
 */
class FactoryParameter
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array                   $parameter
     *
     * @return Service
     */
    public function __invoke(
        ServiceLocatorInterface $serviceLocator,
        array $parameter
    )
    {
        return new Service($parameter);
    }
}