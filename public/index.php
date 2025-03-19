<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$request = Request::createFromGlobals();

$routeCollection = include __DIR__ . '/../routes/api.php';

$requestContext = (new RequestContext())->fromRequest($request);
$urlMatcher = new UrlMatcher(routes: $routeCollection, context: $requestContext);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

try {
    $attributes = $urlMatcher->match(pathinfo: $request->getPathInfo());
    $request->attributes->add($attributes);

    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);
} catch (ResourceNotFoundException) {
    $response = new Response('', status: Response::HTTP_NOT_FOUND);
} catch(Exception $e) {
    $response = new Response('', status: Response::HTTP_INTERNAL_SERVER_ERROR);
}

$response->send();
