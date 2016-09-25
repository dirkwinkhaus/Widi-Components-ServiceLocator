<?php

namespace Widi\Components\ServiceLocator\Exception;

/**
 * Class ServiceFactoryIsNotCallableException
 *
 * @package Widi\Components\ServiceLocator\Exception
 */
class ServiceFactoryIsNotCallableException extends ServiceLocatorException
{

    /**
     * @var int
     */
    const CODE = 400;


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

        parent::__construct(
            'No callable factory for: ' . $message,
            $code,
            $previous
        );
    }
}