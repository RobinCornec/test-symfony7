<?php

namespace App\PandaScore\Hydrator;

use App\PandaScore\DTO\GameDto;
use Generator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class GameHydrator implements HydratorInterface
{
    public function __construct(
        private ValidatorInterface  $validator
    ) {
    }


    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function createAll(
        ResponseInterface $response,
    ): Generator {
        foreach (json_decode($response->getContent(), true) as $psGame) {
            $gameDTO = new GameDto(
                $psGame['current_version'],
                $psGame['id'],
                $psGame['name'],
                $psGame['slug'],
                $psGame['leagues']
            );
            $errors = $this->validator->validate($gameDTO);

            if (count($errors) > 0) {
                return new JsonResponse([], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            yield $gameDTO;
        }
    }
}
