# Widi\Components\ServiceLocator
The service locator component will help you encapsulating your classes.

## Usage
You can simple create an instance of the service loator or create a wrapper to use it as a singleton.

### Code Sample
```
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Widi\Components\ServiceLocator\ServiceLocator;

$services = [
    InvokableService::class => [
        'instance' => InvokableService::class,
    ],
    AnotherInvokableService::class => [
        'instance' => InvokableService::class,
        'options' => [
            'factory'   => false,
            'shared'    => false,
            'parameter' => [],
        ],
    ],
    FactoryService::class   => [
        'instance' => ServiceFactory::class,
        'options' => [
            'factory'   => true,
            'parameter' => [],
        ],
    ],
];

$serviceLocator = new ServiceLocator();
$serviceLocator->setArray($services);
```

## Files
Test
Test/bootstrap.php
Test/phpunit.xml
Test/ServiceLocator
Test/ServiceLocator/Factory.php
Test/ServiceLocator/FactoryParameter.php
Test/ServiceLocator/Service.php
Test/ServiceLocator/ServiceLocatorTest.php
composer.json
composer.lock
readme.md
sample
sample/sample.php
src
src/Exception
src/Exception/CreateServiceException.php
src/Exception/ServiceArrayBadFormatException.php
src/Exception/ServiceFactoryIsNotCallableException.php
src/Exception/ServiceKeyAlreadyInUseException.php
src/Exception/ServiceLocatorException.php
src/Exception/ServiceNotFoundException.php
src/Exception/WrongParameterException.php
src/ServiceLocator.php
src/ServiceLocatorInterface.php