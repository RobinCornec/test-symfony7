<?php

namespace App\PandaScore\Api;

use App\PandaScore\Hydrator\SerieHydrator;
use Generator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SerieApi implements ApiInterface
{
    const PATH = '/series';
    const GAME_PATH = '/videgoames';
    const PAGE_SIZE = 100;

    public function __construct(
        private readonly HttpClientInterface $pandascoreClient,
        private readonly SerieHydrator $serieHydrator,
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

        return $this->serieHydrator->createAll($response);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getAllByGame(int $psGameId): Generator
    {
        $response = $this->pandascoreClient->request(
            'GET',
            GameApi::PATH . "/$psGameId" . self::PATH,
            [
                'query' => [
                    'page[size]' => self::PAGE_SIZE,
                ]
            ]
        );

        yield from $this->serieHydrator->createAll($response);

        $headers = $response->getHeaders();
        $total = (int) $headers['x-total'][0];
        $currentPage = (int) $headers['x-page'][0];

        if ($currentPage * self::PAGE_SIZE < $total) {
            for ($i = $currentPage + 1 ; $i < ceil($total/self::PAGE_SIZE) ; $i++) {
                yield from $this->getByGame($psGameId, $i);
            }
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getByGame(int $psGameId, int $pageNumber = 1): Generator
    {
        $response = $this->pandascoreClient->request(
            'GET',
            GameApi::PATH . "/$psGameId" . self::PATH,
            [
                'query' => [
                    'page[size]' => self::PAGE_SIZE,
                    'page[number]' => $pageNumber
                ]
            ]
        );

        yield from $this->serieHydrator->createAll($response);
    }
}
