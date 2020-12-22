<?php
declare(strict_types=1);

namespace App\Platform\Http;

use RuntimeException;

class Response
{
    private array  $headers;
    private string $content;
    private int    $responseCode;

    public function __construct(string $content = "", int $responseCode = 200, array $headers = [])
    {
        $this->content      = $content;
        $this->responseCode = $responseCode;
        $this->headers      = $headers;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    public function setResponseCode(int $responseCode): self
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function outputHeaders(): void
    {
        if (headers_sent()) {
            throw new RuntimeException('Headers have already been sent');
        }

        array_walk($this->headers, fn($headerContent, $headerName) => header(sprintf('%s: %s', $headerName, $headerContent)));

        return;
    }

    public function outputContent(): void
    {
        echo $this->content;
    }

    public function assignResponseCode()
    {
        http_response_code($this->responseCode);
    }
}