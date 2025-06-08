<?php

namespace App\Services\TInvest;

use App\Http\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\Utils;

class TInvestApi implements TInvestApiInterface
{
    protected Client $client;

    public function __construct()
    {
        $token = config('services.t_invest.token');
        $this->client = (new Client([
            'base_uri' => config('services.t_invest.base_uri'),
            'headers' => [
                'Authorization' => "Bearer $token"
            ]
        ]));
    }

    /**
     * @throws GuzzleException
     * @throws \HttpException
     */
    public function getAccounts(): array
    {
        $response = $this->client->post('rest/tinkoff.public.invest.api.contract.v1.UsersService/GetAccounts', [
            'json' => [
                'status' => 'ACCOUNT_STATUS_OPEN'
            ]
        ]);

        $response = new Response($response);

        if ($response->getCode() !== 200 || empty($response->getBody())) {
            throw new \HttpException('TInvest api error', $response->getCode());
        }

        return array_map(fn(array $account) => $account['id'], $response->getBody()['accounts']);
    }

    public function getPortfolio(array $accountsIds): array
    {
        $promises = [];
        foreach ($accountsIds as $accountId) {
            $promises[$accountId] = $this->client->postAsync('rest/tinkoff.public.invest.api.contract.v1.OperationsService/GetPortfolio', [
                'json' => [
                    'accountId' => (string)$accountId,
                    'currency' => 'USD' // TODO дать возможность выбирать
                ]
            ]);
        }

        $results = Utils::settle($promises)->wait();
        $data = [];
        foreach ($results as $accountId => $result) {
            if ($result['state'] === 'fulfilled') {
                $data[] = json_decode($result['value']->getBody(), true);

            } else {
                throw new \Exception("Ошибка для аккаунта {$accountId}: {$result['reason']}\n");
            }
        }
        return $data;
    }
}

