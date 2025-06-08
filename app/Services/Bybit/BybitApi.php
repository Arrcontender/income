<?php

namespace App\Services\Bybit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use HttpException;

class BybitApi implements BybitApiInterface
{
    const BAPI_RECV_WINDOW = "5000";
    const BAPI_SIGN_TYPE = "2";

    protected Client $client;
    protected string $apiKey;
    protected string $apiSecret;

    public function __construct()
    {
        $this->client = (new Client([
            'base_uri' => config('services.bybit.base_uri'),
        ]));

        $this->apiKey = config('services.bybit.api_key');

        $this->apiSecret = config('services.bybit.api_secret');
    }

    protected function sign(string $method, int $timestamp, ?array $params = []): string
    {
        $query_string = '';

        foreach ($params as $key => $value) {
            $query_string .= sprintf('%s=%s&', $key, $value);
        }

        $query_string = rtrim($query_string, '&');

        if (strtoupper($method) == 'GET') {
            $encoded_params = $query_string;
        } else {
            $encoded_params = empty($params) ? '' : json_encode($params, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $signature_params = $timestamp . $this->apiKey . self::BAPI_RECV_WINDOW . $encoded_params;
        return hash_hmac('sha256', $signature_params, $this->apiSecret);
    }

    /**
     * @throws GuzzleException
     * @throws HttpException
     */
    protected function request(string $method, string $endpoint, array $params = []): BybitResponse
    {
        $timestamp = (int)(microtime(true) * 1000);

        $options = [];
        $options['headers']['X-BAPI-API-KEY'] = $this->apiKey;
        $options['headers']['X-BAPI-SIGN'] = $this->sign($method, $timestamp, $params);
        $options['headers']['X-BAPI-SIGN-TYPE'] = self::BAPI_SIGN_TYPE;
        $options['headers']['X-BAPI-TIMESTAMP'] = $timestamp;
        $options['headers']['X-BAPI-RECV-WINDOW'] = self::BAPI_RECV_WINDOW;

        if (strtoupper($method) == 'GET') {
            $options['query'] = $params;
        } else {
            $options['headers']['Content-Type'] = 'application/json';
            $options['json'] = $params;
        }

        $response = $this->client->request($method, $endpoint, $options);
        $response = new BybitResponse($response);

        if ($response->getHttpResponse()->getStatusCode() != 200) {
            $exception = new \Exception($response->getHttpResponse()->getReasonPhrase(), $response->getHttpResponse()->getStatusCode());
            throw $exception;
        } else if (!$response->isSuccessful()) {
            $exception = new \Exception($response->getApiMessage(), $response->getApiCode());
            throw $exception;
        }

        return $response;
    }

    /**
     * @throws HttpException
     * @throws GuzzleException
     */
    public function getWalletBalance(?array $params = []): array
    {
        $response = $this->request('GET', '/v5/account/wallet-balance', $params);
        return $response->getApiData();
    }
}
