<?php
declare(strict_types=1);

namespace App\Controller;

use App\Platform\Component\Application;
use App\Platform\Http\Response;
use RuntimeException;

class AbstractController
{
    private string $templatesDir;

    public function __construct(Application $application)
    {
        $this->templatesDir = $application->getRootDir() . '/templates';
    }

    protected function render(string $templateFilename, array $templateVars = []): Response
    {
        if (array_key_exists('templateFilename', $templateVars)) {
            throw new RuntimeException('Template variable $templateFilename is reserved and not allowed to use');
        }

        if (array_key_exists('templateVars', $templateVars)) {
            throw new RuntimeException('Template variable $templateVars is reserved and not allowed to use');
        }

        extract($templateVars);

        unset($templateVars);

        ob_start();

        require(sprintf('%s/%s.php', $this->templatesDir, ltrim($templateFilename, '\\/.~')));

        $content = ob_get_contents();

        ob_clean();

        $response = (new Response())
            ->setHeaders([]) // todo: set headers properly
            ->setContent($content);

        return $response;
    }
}