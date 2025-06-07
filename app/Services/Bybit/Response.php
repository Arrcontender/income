<?php

namespace App\Services\Bybit;

use Psr\Http\Message\ResponseInterface;

class Response
{
    const SUCCESS = 0;

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

    public function getApiCode()
    {
        $body = $this->getBody();
        return $body['retCode'] ?? -1;
    }


    public function getApiMessage()
    {
        $body = $this->getBody();
        return $body['retMsg'] ?? '';
    }

    public function isSuccessful(): bool
    {
        if ($this->httpResponse->getStatusCode() == 200) {
            if ($this->getApiCode() == self::SUCCESS) {
                return true;
            }
        }
        return false;
    }

    public function getApiData(): ?array
    {
        $body = $this->getBody();
        if (!isset($body['result'])) {
            return null;
        }
        return $body['result'];
    }
}
