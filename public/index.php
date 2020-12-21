<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Platform\Component\Application;
use App\Platform\Http\Request;

define("ENV", "DEV");

// todo: remove debug messages
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$request = Request::createFromGlobals();

$application = new Application();
$application->setRootDir(realpath(__DIR__ . '/..'));

try {
    $response = $application->handle($request);
} catch (Exception $exception) {
    // todo: handle exception
    $response = $application->handleException($exception);
}

$response->outputHeaders();
$response->assignResponseCode();
$response->outputContent();