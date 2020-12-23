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
use Exception;
use Throwable;

class Application
{
    const DEFAULT_CONTROLLER_NAMESPACE = 'App\Controller';
    const DEFAULT_CONTROLLER           = 'ApplicationController';
    const DEFAULT_ACTION               = 'indexAction';

    private string $rootDir;

    public function handle(Request $request): Response
    {
        // todo: refactor container initialization
        $configManager = ConfigurationManager::loadConfiguration();

        $container = new Container();

        $container->set(Request::class, $request);
        $container->set(Application::class, $this);
        $container->set(
            DatabaseConnectionInterface::class,
            DatabaseConnectionFactory::get($configManager->get('dbType'), $configManager->get('dbSchema'))
        );

        $controller = self::getControllerFromRequest($request);

        return $container->call($controller);
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
            return new JsonResponse([], 500);
        }

        return new JsonResponse($exception->getRfcFields(), $exception->getCode());
    }

    private function getControllerFromRequest(Request $originalRequest): string
    {
        return $this->mapUri($originalRequest->server->get('REQUEST_URI'));
    }

    private function mapUri(string $uri): string
    {
        // todo: move routes array to config
        $routes = [
            '/api/v1/statistics/orders'    => 'App\Controller\Api\v1\StatisticsController::ordersAction',
            '/api/v1/statistics/revenues'  => 'App\Controller\Api\v1\StatisticsController::revenuesAction',
            '/api/v1/statistics/customers' => 'App\Controller\Api\v1\StatisticsController::customersAction',
        ];

        $requestedPath = '/' . strtolower(trim(explode('?', $uri, 2)[0], '/'));
        if (array_key_exists($requestedPath, $routes)) {
            return $routes[$requestedPath];
        }

        [$controllerName, $actionName] = $this->guessDefaultUriMapping($requestedPath);
        if (method_exists($controllerName, $actionName)) {
            return sprintf('%s::%s', $controllerName, $actionName);
        }

        throw new Exception(sprintf('Route "%s" not found', $requestedPath), 404);
    }

    private function guessDefaultUriMapping(string $requestedPath): array
    {
        $pathComponents = explode('/', trim($requestedPath, '/'));

        if (count($pathComponents) === 1) {
            $pathComponents[0] = empty($pathComponents[0]) ? self::DEFAULT_ACTION : ($pathComponents[0] . 'Action');

            return [
                self::DEFAULT_CONTROLLER_NAMESPACE . '\\' . self::DEFAULT_CONTROLLER,
                $pathComponents[0]
            ];
        }

        return ['', ''];
    }

    public function getRootDir(): string
    {
        return $this->rootDir;
    }

    public function setRootDir(string $dir): void
    {
        $this->rootDir = $dir;
    }
}