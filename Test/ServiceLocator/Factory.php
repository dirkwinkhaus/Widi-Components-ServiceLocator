<?php

namespace Widi\Components\Test\ServiceLocator;

use Widi\Components\ServiceLocator\ServiceLocatorInterface;

require_once(__DIR__ . '/Service.php');

/**
 * Class Factory
 *
 * @package Widi\Components\Test\ServiceLocator
 */
class Factory
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Service
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {

        return new Service();
    }
}