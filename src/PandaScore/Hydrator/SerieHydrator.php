<?php

namespace App\PandaScore\Hydrator;

use App\PandaScore\DTO\SerieDto;
use App\PandaScore\Enum\WinnerTypeEnum;
use DateTimeImmutable;
use Exception;
use Generator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class SerieHydrator implements HydratorInterface
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
     * @throws Exception
     */
    public function createAll(
        ResponseInterface $response,
    ): Generator {
        foreach (json_decode($response->getContent(), true) as $psSerie) {
            dump($psSerie['winner_type']);
            $serieDTO = new SerieDto(
                new DateTimeImmutable($psSerie['begin_at']),
                new DateTimeImmutable($psSerie['end_at']),
                $psSerie['full_name'],
                $psSerie['id'],
                $psSerie['league_id'],
                new DateTimeImmutable($psSerie['modified_at']),
                $psSerie['name'],
                $psSerie['season'],
                $psSerie['slug'],
                (object) $psSerie['videogame_title'],
                $psSerie['winner_id'],
                WinnerTypeEnum::from($psSerie['winner_type']),
                $psSerie['year'],
                (object) $psSerie['league'],
                (object) $psSerie['videogame'],
                array_map(function (array $element) {
                    return (object) $element;
                }, $psSerie['tournaments']),
            );
            $errors = $this->validator->validate($serieDTO);

            if (count($errors) > 0) {
                return new JsonResponse([], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            yield $serieDTO;
        }
    }
}
