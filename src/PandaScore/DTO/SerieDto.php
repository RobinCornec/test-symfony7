<?php

namespace App\PandaScore\DTO;

use App\PandaScore\Enum\WinnerTypeEnum;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

readonly class SerieDto implements DtoInterface
{

    /**
     * @param DateTimeImmutable|null $beginAt
     * @param DateTimeImmutable|null $endAt
     * @param string $fullName
     * @param int $id
     * @param int $leagueId
     * @param DateTimeImmutable|null $modifiedAt
     * @param string|null $name
     * @param string|null $season
     * @param string $slug
     * @param object|null $videoGameTitle
     * @param int|null $winnerId
     * @param WinnerTypeEnum|null $winnerType
     * @param int|null $year
     * @param object $league
     * @param object $videogame
     * @param object[] $tournaments
     */
    public function __construct(
        public ?DateTimeImmutable $beginAt,
        public ?DateTimeImmutable $endAt,
        #[Assert\NotNull()]
        public string $fullName,
        #[Assert\Positive()]
        #[Assert\NotNull()]
        public int $id,
        #[Assert\Positive()]
        #[Assert\NotNull()]
        public int $leagueId,
        public ?DateTimeImmutable $modifiedAt,
        public ?string $name,
        public ?string $season,
        #[Assert\NotNull()]
        public string $slug,
        public ?object $videoGameTitle,
        public ?int $winnerId,
        public ?WinnerTypeEnum $winnerType,
        #[Assert\Positive()]
        public ?int $year,
        public object $league,
        public object $videogame,
        public array $tournaments,
    ) {
    }
}
