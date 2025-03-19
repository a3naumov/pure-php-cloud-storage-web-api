<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$request = Request::createFromGlobals();

$routeCollection = new RouteCollection();
$routeCollection->add(name: 'hello', route: new Route(
    path: '/hello/{name}',
    defaults: ['name' => 'World'],
));

$requestContext = (new RequestContext())->fromRequest($request);
$urlMatcher = new UrlMatcher(routes: $routeCollection, context: $requestContext);

try {
    $attributes = $urlMatcher->match(pathinfo: $request->getPathInfo());
    $response = new Response(sprintf('Hello, %s', htmlspecialchars($attributes['name'] ?? '', ENT_QUOTES, 'UTF-8')));
} catch (ResourceNotFoundException) {
    $response = new Response('', status: Response::HTTP_NOT_FOUND);
} catch(Exception $e) {
    $response = new Response('', status: Response::HTTP_INTERNAL_SERVER_ERROR);
}

$response->send();
