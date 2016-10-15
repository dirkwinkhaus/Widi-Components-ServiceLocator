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
    const CREATED_INSTANCE = 'created_instance';

    /**
     * @var string
     */
    const INSTANCE = 'instance';

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
     * ServiceLocator constructor.
     *
     * @param array $services
     */
    public function __construct(array $services = [])
    {

        $this->setArray($services);
    }


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

        return $this->setService($serviceKey, $factoryOrInvokable, $options);
    }


    /**
     * @param string $serviceKey
     * @param mixed  $instance
     *
     * @return ServiceLocator
     */
    public function setInstance($serviceKey, $instance)
    {

        return $this->setService($serviceKey, null, [], $instance);
    }


    /**
     * @param string $serviceKey
     * @param string $factoryOrInvokable
     * @param array  $options
     * @param null   $instance
     *
     * @return $this
     * @throws ServiceKeyAlreadyInUseException
     * @throws WrongParameterException
     */
    protected function setService(
        $serviceKey,
        $factoryOrInvokable,
        array $options = [],
        $instance = null
    ) {

        if ($this->has($serviceKey)) {
            throw new ServiceKeyAlreadyInUseException($serviceKey);
        }

        if (empty($serviceKey)) {
            throw new WrongParameterException($serviceKey);
        }

        $this->services[$serviceKey] = [
            self::INSTANCE         => $factoryOrInvokable,
            self::CREATED_INSTANCE => $instance,
            self::OPTIONS          => $options,
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

            if (!isset($serviceOptions[self::INSTANCE])) {
                throw new ServiceArrayBadFormatException();
            }

            $options = $this->getValue($serviceOptions[self::OPTIONS], []);

            $this->set(
                $serviceKey,
                $serviceOptions[self::INSTANCE],
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
            if ($this->services[$serviceKey][self::CREATED_INSTANCE] !== null) {
                return $this->services[$serviceKey][self::CREATED_INSTANCE];
            }
        }

        if ($isFactory) {
            $this->services[$serviceKey][self::CREATED_INSTANCE]
                = $this->createFromFactory(
                $serviceInformation,
                $parameter
            );
        } else {
            $this->services[$serviceKey][self::CREATED_INSTANCE]
                = $this->createFromInvokable(
                $serviceInformation,
                $parameter
            );
        }

        return $this->services[$serviceKey][self::CREATED_INSTANCE];
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
            $factory = new $serviceInformation[self::INSTANCE]();
        } catch (\Exception $exception) {
            throw new CreateServiceException(
                $serviceInformation[self::INSTANCE]
            );
        }

        if (!is_callable($factory)) {
            throw new ServiceFactoryIsNotCallableException(self::INSTANCE);
        }

        try {
            if ($parameter !== []) {
                return $factory->__invoke($this, $parameter);
            } else {
                return $factory->__invoke($this);
            }
        } catch (\Exception $exception) {
            throw new CreateServiceException(
                $serviceInformation[self::INSTANCE]
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
                return new $serviceInformation[self::INSTANCE]($parameter);
            } else {
                return new $serviceInformation[self::INSTANCE]();
            }
        } catch (\Exception $exception) {
            throw new CreateServiceException(
                $serviceInformation[self::INSTANCE]
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