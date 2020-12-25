<?php
declare(strict_types=1);

namespace App\Platform\Http;

use App\Platform\DataStructure\ImmutableCollection;

class Request
{
    private static Request $instance;

    public ImmutableCollection $query;
    public ImmutableCollection $request;
    public ImmutableCollection $files;
    public ImmutableCollection $server;

    private function __construct(array $get, array $post, array $files, array $server)
    {
        $this->query   = new ImmutableCollection($get);
        $this->request = new ImmutableCollection($post);
        $this->files   = new ImmutableCollection($files);
        $this->server  = new ImmutableCollection($server);
    }

    public static function createFromGlobals(): Request
    {
        return self::$instance ?? new Request($_GET, $_POST, $_FILES, $_SERVER);
    }

    public function getMethod(): string
    {
        return $this->server->get('REQUEST_METHOD');
    }

    public function isAjax()
    {
        return strtolower($this->server->get('HTTP_X_REQUESTED_WITH', '')) === 'xmlhttprequest';
    }
}