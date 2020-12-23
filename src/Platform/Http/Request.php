<?php
declare(strict_types=1);

namespace App\Platform\Http;

use App\Platform\DataStructure\Collection;

class Request
{
    private static Request $instance;

    public Collection $query;
    public Collection $request;
    public Collection $files;
    public Collection $server;

    private function __construct(array $get, array $post, array $files, array $server)
    {
        $this->query   = new Collection($get);
        $this->request = new Collection($post);
        $this->files   = new Collection($files);
        $this->server  = new Collection($server);
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
        return strtolower($this->server->get('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest';
    }
}