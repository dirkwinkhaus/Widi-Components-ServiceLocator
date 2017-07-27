<?php

namespace Widi\Components\ServiceLocator;

use Psr\Container\ContainerInterface;

/**
 * Class FactoryParameter
 *
 * @package Widi\Components\Test\ServiceLocator
 */
class FactoryParameter
{

    /**
     * @param ContainerInterface $serviceLocator
     * @param array                   $parameter
     *
     * @return Service
     */
    public function __invoke(
        ContainerInterface $serviceLocator,
        array $parameter
    )
    {
        return new Service($parameter);
    }
}