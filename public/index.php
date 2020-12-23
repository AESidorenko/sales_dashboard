<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Platform\Component\Application;
use App\Platform\Http\Request;

define("ENV", "DEV");

$request = Request::createFromGlobals();

$application = new Application();
$application->setRootDir(realpath(__DIR__ . '/..'));

try {
    $response = $application->handle($request);
} catch (Exception $exception) {
    $response = $application->handleException($exception, $request);
}

$response->outputHeaders();
$response->assignResponseCode();
$response->outputContent();