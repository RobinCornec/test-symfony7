<?php

namespace App\PandaScore\DTO;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

readonly class GameDto implements DtoInterface
{
    /**
     * @param string|null $currentVersion
     * @param int $id
     * @param string $name
     * @param string $slug
     * @param array{
     *     id: int,
     *     imageUrl: string|null,
     *     modifiedAt: DateTimeImmutable,
     *     name: string,
     *     slug: string,
     *     url: string|null,
     *     series: array
     * }[] $leagues
     */
    public function __construct(
        public ?string $currentVersion,
        #[Assert\Positive()]
        #[Assert\NotNull()]
        public int $id,
        public string $name,
        #[Assert\NotNull()]
        public string $slug,
        #[Assert\NotNull()]
        public array $leagues,
    ) {
    }
}