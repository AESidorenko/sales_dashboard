<?php
declare(strict_types=1);

namespace App\Platform\Http;

use App\Platform\DataStructure\ImmutableCollection;

class Request
{
    public ImmutableCollection $query;
    public ImmutableCollection $request;
    public ImmutableCollection $files;
    public ImmutableCollection $server;

    public function __construct()
    {
        $this->query   = new ImmutableCollection($_GET);
        $this->request = new ImmutableCollection($_POST);
        $this->files   = new ImmutableCollection($_FILES);
        $this->server  = new ImmutableCollection($_SERVER);
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