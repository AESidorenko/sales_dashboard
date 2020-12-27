<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Platform\Component\Application;
use App\Platform\Http\Request;
use DI\Container;

define("ENV", "DEV");

try {
    $container = new Container();

    $request     = $container->get(Request::class);
    $application = $container->get(Application::class);

    $response = $application->handle($request);
} catch (Exception $exception) {
    $response = $application->handleException($exception, $request);
}

$response->outputHeaders();
$response->assignResponseCode();
$response->outputContent();