<?php

namespace App\Platform\Component;

use RuntimeException;

class Router
{
    const DEFAULT_CONTROLLER_NAMESPACE = 'App\Controller';
    const DEFAULT_CONTROLLER           = 'ApplicationController';
    const DEFAULT_ACTION               = 'indexAction';

    private $routes;

    public function __construct(ConfigurationManager $configurationManager)
    {
        if (!$configurationManager->has('routes')) {
            $this->routes = [];

            return;
        }

        $this->routes = $configurationManager->get('routes');

        if (!is_array($this->routes)) {
            throw new RuntimeException('Invalid routes configuration type: array expected');
        }
    }

    public function mapUriToController(string $uri): string
    {
        $requestedPath = self::normalizeUri($uri);
        if ($this->isRouteConfiguredExplicitly($requestedPath)) {
            return $this->routes[$requestedPath];
        }

        [$controllerName, $actionName] = $this->guessDefaultUriMapping($requestedPath);
        if (self::isRouteMatchingControllerAndAction($controllerName, $actionName)) {
            return sprintf('%s::%s', $controllerName, $actionName);
        }

        throw new RuntimeException(sprintf('Page/route "%s" not found', $requestedPath), 404);
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

    private static function normalizeUri(string $uri): string
    {
        return '/' . strtolower(trim(explode('?', $uri, 2)[0], '/'));
    }

    private function isRouteConfiguredExplicitly(string $route): bool
    {
        return array_key_exists($route, $this->routes);
    }

    private function isRouteMatchingControllerAndAction(string $controllerName, string $actionName): bool
    {
        $namespaceParts = explode('\\', $controllerName, 3);

        if ($namespaceParts === false) {
            return false;
        }

        if (!isset($namespaceParts[1]) || $namespaceParts[1] !== 'Controller') {
            throw new RuntimeException('Controller class must be in <App>\Controller namespace');
        }

        return method_exists($controllerName, $actionName);
    }
}