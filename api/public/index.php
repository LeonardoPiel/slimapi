<?php

use App\Controllers\ProductIndex;
use App\Controllers\Products;
use App\Middleware\AddJsonResponseHeader;
use App\Middleware\GetProduct;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;

define('APP_ROOT', dirname(__DIR__));
require  APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder;
$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$collector = $app->getRouteCollector();
$collector->setDefaultInvocationStrategy(new RequestResponseArgs);
$app->addBodyParsingMiddleware();
$error_middleware = $app->addErrorMiddleware(true, true, true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');

$app->add(new AddJsonResponseHeader);
$app->group('/api/products', function ($group) {
    
    $group->get('', ProductIndex::class);
    $group->post('', [Products::class, 'create']);

    $group->group('/{id:[0-9]+}', function ($group) {
        $group->get('', Products::class . ':show');
        $group->patch('', [Products::class, 'update']);
        $group->delete('', [Products::class, 'delete']);
    })->add(GetProduct::class);;
});
$app->run();
