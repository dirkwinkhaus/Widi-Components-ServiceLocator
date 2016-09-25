<?php

namespace Widi\Components\ServiceLocator;

use Widi\Components\ServiceLocator\Exception\CreateServiceException;
use Widi\Components\ServiceLocator\Exception\ServiceArrayBadFormatException;
use Widi\Components\ServiceLocator\Exception\ServiceFactoryIsNotCallableException;
use Widi\Components\ServiceLocator\Exception\ServiceKeyAlreadyInUseException;
use Widi\Components\ServiceLocator\Exception\ServiceNotFoundException;
use Widi\Components\ServiceLocator\Exception\WrongParameterException;

/**
 * Class ServiceLocator
 *
 * @package Widi\ServiceLocator
 */
class ServiceLocator implements ServiceLocatorInterface
{

    /**
     * @var string
     */
    const OPTIONS = 'options';

    /**
     * @var string
     */
    const INSTANCE = 'instance';

    /**
     * @var string
     */
    const SERVICE = 'service';

    /**
     * @var string
     */
    const OPTIONS_FACTORY = 'factory';

    /**
     * @var string
     */
    const OPTIONS_SHARED = 'shared';

    /**
     * @var string
     */
    const OPTIONS_PARAMETER = 'parameter';

    /**
     * @var array
     */
    protected $services;


    /**
     * @param string $serviceKey
     *
     * @return mixed
     * @throws ServiceNotFoundException
     */
    public function get($serviceKey)
    {

        if ($this->has($serviceKey)) {
            return $this->provide($serviceKey);
        } else {
            throw new ServiceNotFoundException($serviceKey);
        }
    }


    /**
     * @param string $serviceKey
     *
     * @return bool
     */
    public function has($serviceKey)
    {

        return isset($this->services[$serviceKey]);
    }


    /**
     * @param string $serviceKey
     * @param string $factoryOrInvokable
     * @param array  $options
     *
     * @return ServiceLocator
     * @throws ServiceKeyAlreadyInUseException
     * @throws WrongParameterException
     */
    public function set($serviceKey, $factoryOrInvokable, array $options = [])
    {

        if ($this->has($serviceKey)) {
            throw new ServiceKeyAlreadyInUseException($serviceKey);
        }

        if (empty($serviceKey)) {
            throw new WrongParameterException($serviceKey);
        }

        $this->services[$serviceKey] = [
            self::SERVICE => $factoryOrInvokable,
            self::INSTANCE => null,
            self::OPTIONS => $options,
        ];

        return $this;
    }


    /**
     * @param array $services
     *
     * @return $this
     * @throws ServiceArrayBadFormatException
     * @throws ServiceKeyAlreadyInUseException
     */
    public function setArray(array $services)
    {

        foreach ($services as $serviceKey => $serviceOptions) {

            if (!isset($serviceOptions[self::SERVICE])) {
                throw new ServiceArrayBadFormatException();
            }

            $options = $this->getValue($serviceOptions[self::OPTIONS], []);

            $this->set(
                $serviceKey,
                $serviceOptions[self::SERVICE],
                $options
            );

        }

        return $this;
    }


    /**
     * @return ServiceLocator
     */
    public function clear()
    {
        $this->services = [];

        return $this;
    }


    /**
     * @param $serviceKey
     *
     * @return
     * @throws ServiceFactoryIsNotCallableException
     */
    protected function provide($serviceKey)
    {

        $serviceInformation = $this->services[$serviceKey];

        $isFactory = $this->getValue(
            $serviceInformation[self::OPTIONS][self::OPTIONS_FACTORY],
            false
        );
        $isShared  = $this->getValue(
            $serviceInformation[self::OPTIONS][self::OPTIONS_SHARED],
            true
        );
        $parameter = $this->getValue(
            $serviceInformation[self::OPTIONS][self::OPTIONS_PARAMETER],
            []
        );

        if ($isShared) {
            if ($this->services[$serviceKey][self::INSTANCE] !== null) {
                return $this->services[$serviceKey][self::INSTANCE];
            }
        }

        if ($isFactory) {
            $this->services[$serviceKey][self::INSTANCE]
                = $this->createFromFactory(
                $serviceInformation,
                $parameter
            );
        } else {
            $this->services[$serviceKey][self::INSTANCE]
                = $this->createFromInvokable(
                $serviceInformation,
                $parameter
            );
        }

        return $this->services[$serviceKey][self::INSTANCE];
    }


    /**
     * @param array $serviceInformation
     * @param array $parameter
     *
     * @return mixed
     * @throws CreateServiceException
     * @throws ServiceFactoryIsNotCallableException
     */
    protected function createFromFactory(
        array $serviceInformation,
        array $parameter
    ) {

        try {
            $factory = new $serviceInformation[self::SERVICE]();
        } catch (\Exception $exception) {
            throw new CreateServiceException(
                $serviceInformation[self::SERVICE]
            );
        }

        if (!is_callable($factory)) {
            throw new ServiceFactoryIsNotCallableException(self::SERVICE);
        }

        try {
            if ($parameter !== []) {
                return $factory->__invoke($this, $parameter);
            } else {
                return $factory->__invoke($this);
            }
        } catch (\Exception $exception) {
            throw new CreateServiceException(
                $serviceInformation[self::SERVICE]
            );
        }
    }


    /**
     * @param array $serviceInformation
     * @param array $parameter
     *
     * @return mixed
     * @throws CreateServiceException
     */
    protected function createFromInvokable(
        array $serviceInformation,
        array $parameter
    ) {

        try {
            if ($parameter !== []) {
                return new $serviceInformation[self::SERVICE]($parameter);
            } else {
                return new $serviceInformation[self::SERVICE]();
            }
        } catch (\Exception $exception) {
            throw new CreateServiceException(
                $serviceInformation[self::SERVICE]
            );
        }
    }


    /**
     * @param $variable
     * @param $default
     *
     * @return mixed|null
     */
    protected function getValue(& $variable, $default = null)
    {

        if (isset($variable)) {
            return $variable;
        } else {
            return $default;
        }
    }
}