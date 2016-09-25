<?php

namespace Widi\Components\ServiceLocator;

/**
 * Interface ServiceLocatorInterface
 *
 * @package Widi\ServiceLocator
 */
interface ServiceLocatorInterface
{

    /**
     * @param string $serviceKey
     *
     * @return mixed
     */
    public function get($serviceKey);


    /**
     * @param string $serviceKey
     *
     * @return bool
     */
    public function has($serviceKey);


    /**
     * @param string $serviceKey
     * @param string $factoryOrInvokable
     * @param array  $options
     *
     * @return ServiceLocatorInterface
     */
    public function set($serviceKey, $factoryOrInvokable, array $options = []);
}