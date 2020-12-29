<?php
declare(strict_types=1);

namespace App\Platform\Component;

use App\Controller\ErrorHandlerController;
use App\Exception\HttpProblemJsonException;
use App\Platform\Database\DatabaseConnectionFactory;
use App\Platform\Database\DatabaseConnectionInterface;
use App\Platform\Http\JsonResponse;
use App\Platform\Http\Request;
use App\Platform\Http\Response;
use DI\Container;
use RuntimeException;
use Throwable;

class Application
{
    private string                      $rootDir;
    private Container                   $container;
    private Router                      $router;

    public function __construct(
        Container $container,
        ConfigurationManager $configurationManager,
        Router $router
    ) {
        $this->container = $container;
        $this->router    = $router;
        $this->rootDir   = realpath(__DIR__ . '/../../..');

        if (!$configurationManager->has('dbConnectionParameters')) {
            throw new RuntimeException('Config file key not found: dbConnectionParameters');
        }

        $dbConnection = DatabaseConnectionFactory::createDatabaseConnection($configurationManager->get('dbConnectionParameters'));

        $container->set(
            DatabaseConnectionInterface::class,
            $dbConnection
        );
    }

    public function handle(Request $request): Response
    {
        $controller = $this->getControllerFromRequest($request);

        return $this->container->call($controller);
    }

    public function handleException(Throwable $exception, Request $request): Response
    {
        if ($request->isAjax()) {
            return $this->handleAjaxException($exception);
        }

        if ($exception->getCode() < 400) {
            $message = 'Internal server error';
        } else {
            $message = $exception->getMessage();
        }

        return (new ErrorHandlerController($this))->renderErrorPage($message);
    }

    public function handleAjaxException(Throwable $exception): JsonResponse
    {
        if (!$exception instanceof HttpProblemJsonException) {
            if ($exception->getCode() < 100) {
                $title = 'Internal server error';
                $code  = 500;
            } else {
                $title = $exception->getMessage();
                $code  = $exception->getCode();
            }

            return new JsonResponse([HttpProblemJsonException::FIELD_TITLE => $title], $code, ['Content-Type' => 'application/problem+json']);
        }

        return new JsonResponse($exception->getRfcFields(), $exception->getCode(), ['Content-Type' => 'application/problem+json']);
    }

    private function getControllerFromRequest(Request $originalRequest): string
    {
        return $this->router->mapUriToController($originalRequest->server->get('REQUEST_URI'));
    }

    public function getRootDir(): string
    {
        return $this->rootDir;
    }
}