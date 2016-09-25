<?php

namespace Widi\Components\ServiceLocator\Exception;

/**
 * Class WrongParameterException
 *
 * @package Widi\Components\ServiceLocator\Exception
 */
class WrongParameterException extends ServiceLocatorException
{

    /**
     * @var int
     */
    const CODE = 600;


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

        parent::__construct('Wrong parameter: ' . $message, $code, $previous);
    }
}