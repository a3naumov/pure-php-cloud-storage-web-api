<?php

declare(strict_types=1);

use App\Controller\BaseController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routeCollection = new RouteCollection();

$routeCollection->add(name: 'hello', route: new Route(
    path: '/hello/{name}',
    defaults: [
        'name' => 'World',
        '_controller' => [BaseController::class, 'hello'],
    ],
));

return $routeCollection;