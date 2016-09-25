<?php

namespace Widi\Components\ServiceLocator\Exception;

/**
 * Class ServiceKeyAlreadyInUseException
 *
 * @package Widi\ServiceLocator\Exception
 */
class ServiceKeyAlreadyInUseException extends ServiceLocatorException
{

    /**
     * @var int
     */
    const CODE = 300;


    /**
     * ServiceNotFoundException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(
        $message,
        $code = self::CODE,
        \Exception $previous = null
    ) {

        parent::__construct(
            'Service key already in use: ' . $message,
            $code,
            $previous
        );
    }
}