<?php

namespace Widi\Components\ServiceLocator\Exception;

/**
 * Class ServiceNotFoundException
 *
 * @package Widi\ServiceLocator\Exception
 */
class ServiceNotFoundException extends ServiceLocatorException
{

    /**
     * @var int
     */
    const CODE = 100;


    /**
     * ServiceNotFoundException constructor.
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct(
        $message,
        $code = self::CODE,
        \Exception $previous = null
    ) {

        parent::__construct('Service not found: ' . $message, $code, $previous);
    }
}