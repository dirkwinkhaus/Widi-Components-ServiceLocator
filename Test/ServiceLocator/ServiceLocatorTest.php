<?php

namespace Widi\Components\Test\ServiceLocator;

use PHPUnit\Framework\TestCase;
use Widi\Components\ServiceLocator\Exception\ServiceArrayBadFormatException;
use Widi\Components\ServiceLocator\Exception\ServiceFactoryIsNotCallableException;
use Widi\Components\ServiceLocator\Exception\ServiceKeyAlreadyInUseException;
use Widi\Components\ServiceLocator\Exception\ServiceNotFoundException;
use Widi\Components\ServiceLocator\Exception\WrongParameterException;
use Widi\Components\ServiceLocator\ServiceLocator;

require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/Factory.php');
require_once(__DIR__ . '/FactoryParameter.php');
require_once(__DIR__ . '/Service.php');

/**
 * Class ServiceLocatorTest
 *
 * @package Widi\Components\Test\ServiceLocator
 */
class ServiceLocatorTest extends TestCase
{

    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    /**
     * @var array
     */
    protected $parameter;


    /**
     * setup service locator
     */
    public function setUp()
    {

        $this->serviceLocator = new ServiceLocator();
        $this->parameter      = [
            'spl_object_hash' => spl_object_hash($this),
            'time'            => time(),
        ];

    }


    public function testSharedService()
    {

        $this->serviceLocator->set(Service::class, Service::class);

        /**
         * @var Service $service
         */
        $service = $this->serviceLocator->get(Service::class);

        /**
         * @var Service $sharedService
         */
        $sharedService = $this->serviceLocator->get(Service::class);

        $this->assertSame($service, $sharedService);
    }


    public function testNonSharedServiceWithParameter()
    {

        $this->serviceLocator->set(
            Service::class,
            Service::class,
            [
                'shared'    => false,
                'parameter' => $this->parameter,
            ]
        );

        /**
         * @var Service $service
         */
        $service = $this->serviceLocator->get(Service::class);

        /**
         * @var Service $sharedService
         */
        $sharedService = $this->serviceLocator->get(Service::class);

        $this->assertNotSame($service, $sharedService);
        $this->assertEquals($service->getParameter(), $this->parameter);
    }


    public function testFactory()
    {

        $this->serviceLocator->set(
            Service::class,
            Factory::class,
            [
                'factory' => true,
            ]
        );

        /**
         * @var Service $service
         */
        $service = $this->serviceLocator->get(Service::class);

        $this->assertInstanceOf(
            '\Widi\Components\Test\ServiceLocator\Service',
            $service
        );

    }


    public function testFactoryParameter()
    {

        $this->serviceLocator->set(
            Service::class,
            FactoryParameter::class,
            [
                'factory'   => true,
                'parameter' => $this->parameter,
            ]
        );

        /**
         * @var Service $service
         */
        $service = $this->serviceLocator->get(Service::class);

        $this->assertEquals($service->getParameter(), $this->parameter);
    }

    public function testArraySet()
    {
        $this->serviceLocator->clear();
        $this->serviceLocator->setArray([
            Service::class => [
                'instance' => Service::class,
            ],
            'nonShared' => [
                'instance' => Service::class,
                'options' => [
                    'factory'   => false,
                    'shared'    => false,
                    'parameter' => $this->parameter,
                ],
            ],
            'Factory'   => [
                'instance' => Factory::class,
                'options' => [
                    'factory'   => true,
                    'parameter' => [],
                ],
            ],
        ]);

        $service = $this->serviceLocator->get(Service::class);
        $sameService = $this->serviceLocator->get(Service::class);
        $this->assertSame($service, $sameService);

        $service = $this->serviceLocator->get('nonShared');
        $sameService = $this->serviceLocator->get('nonShared');
        $this->assertNotSame($service, $sameService);

        $service = $this->serviceLocator->get('Factory');
        $sameService = $this->serviceLocator->get('Factory');
        $this->assertSame($service, $sameService);
    }


    public function testWrongParameterException()
    {

        $this->expectException(WrongParameterException::class);

        $this->serviceLocator->set(
            '',
            FactoryParameter::class,
            [
                'factory'   => true,
                'parameter' => $this->parameter,
            ]
        );

    }


    public function testServiceNotFoundException()
    {

        $this->expectException(ServiceNotFoundException::class);

        $this->serviceLocator->get('serviceNotFound');

    }


    public function testServiceKeyAlreadyInUseException()
    {

        $this->expectException(ServiceKeyAlreadyInUseException::class);

        $this->serviceLocator->set('alreadyInUse', Service::class);
        $this->serviceLocator->set('alreadyInUse', Service::class);
    }


    public function testServiceFactoryIsNotCallableException()
    {

        $this->expectException(ServiceFactoryIsNotCallableException::class);

        $this->serviceLocator->set(
            Service::class,
            Service::class,
            [
                'factory'   => true,
                'parameter' => $this->parameter,
            ]
        );

        $this->serviceLocator->get(Service::class);
    }


    public function testServiceArrayBadFormatException()
    {

        $this->expectException(ServiceArrayBadFormatException::class);

        $this->serviceLocator->setArray(
            [
                'instance' => [],
            ]
        );

    }
}