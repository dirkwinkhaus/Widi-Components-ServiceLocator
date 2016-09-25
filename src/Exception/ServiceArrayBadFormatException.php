<?php

namespace Widi\Components\ServiceLocator\Exception;

/**
 * Class ServiceArrayBadFormatException
 *
 * @package Widi\ServiceLocator\Exception
 */
class ServiceArrayBadFormatException extends ServiceLocatorException
{

    /**
     * @var int
     */
    const CODE = 200;

    /**
     * @var string
     */
    const MESSAGE = 'Service array bad formated.';


    /**
     * ServiceNotFoundException constructor.
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct(
        $message = self::MESSAGE,
        $code = self::CODE,
        \Exception $previous = null
    ) {

        parent::__construct($message, $code, $previous);
    }
}