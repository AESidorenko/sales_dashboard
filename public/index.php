<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Platform\Component\Application;
use App\Platform\Http\Request;
use DI\Container;

define("ENV", "DEV");

try {
    $container = new Container();

    $request = Request::createFromGlobals();
    $container->set(Request::class, $request);

    $application = $container->get(Application::class);
    $response    = $application->handle($request);
} catch (Exception $exception) {
    $response = $application->handleException($exception, $request);
}

$response->outputHeaders();
$response->assignResponseCode();
$response->outputContent();