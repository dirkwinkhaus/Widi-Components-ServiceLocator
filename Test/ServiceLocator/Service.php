<?php

namespace Widi\Components\Test\ServiceLocator;

/**
 * Class Service
 *
 * @package Widi\Components\Test\ServiceLocator
 */
class Service
{

    /**
     * @var array
     */
    protected $parameter;


    /**
     * ServiceParameter constructor.
     *
     * @param array $parameter
     */
    public function __construct(array $parameter = [])
    {

        $this->parameter = $parameter;
    }


    /**
     * @return array
     */
    public function getParameter()
    {

        return $this->parameter;
    }
}