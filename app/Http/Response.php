<?php

namespace App\Http;

use Psr\Http\Message\ResponseInterface;

class Response
{
    protected ResponseInterface $httpResponse;

    protected ?array $body = null;

    public function __construct(ResponseInterface $guzzleResponse)
    {
        $this->httpResponse = $guzzleResponse;
    }

    public function getHttpResponse(): ResponseInterface
    {
        return $this->httpResponse;
    }

    public function getBody()
    {
        if (is_null($this->body)) {
            $this->body = json_decode($this->httpResponse->getBody()->getContents(), true);
        }
        return $this->body;
    }

    public function getCode()
    {
        return $this->httpResponse->getStatusCode();
    }
}
