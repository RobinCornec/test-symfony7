<?php

namespace App\PandaScore\Api;

use App\PandaScore\Hydrator\GameHydrator;
use Generator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GameApi implements ApiInterface
{
    const PATH = '/videogames';

    public function __construct(
        private readonly HttpClientInterface $pandascoreClient,
        private readonly GameHydrator $gameHydrator,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getAll(): Generator
    {
        $response = $this->pandascoreClient->request(
            'GET',
            self::PATH,
        );

        return $this->gameHydrator->createAll($response);
    }
}
