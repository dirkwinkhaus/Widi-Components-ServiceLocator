<?php

namespace Widi\Components\ServiceLocator\Exception;

/**
 * Class CreateServiceException
 *
 * @package Widi\Components\ServiceLocator\Exception
 */
class CreateServiceException extends ServiceLocatorException
{

    /**
     * @var int
     */
    const CODE = 500;


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
            'Could not create service: ' . $message,
            $code,
            $previous
        );
    }
}