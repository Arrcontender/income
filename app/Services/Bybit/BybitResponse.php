<?php

namespace App\Services\Bybit;

use App\Http\Response;
use Psr\Http\Message\ResponseInterface;

class BybitResponse extends Response
{
    const SUCCESS = 0;

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
