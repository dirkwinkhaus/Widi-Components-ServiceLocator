<?php

namespace Widi\Components\ServiceLocator;

use Psr\Container\ContainerInterface;

/**
 * Class Factory
 *
 * @package Widi\Components\Test\ServiceLocator
 */
class Factory
{

    /**
     * @param ContainerInterface $serviceLocator
     *
     * @return Service
     */
    public function __invoke(ContainerInterface $serviceLocator)
    {

        return new Service();
    }
}