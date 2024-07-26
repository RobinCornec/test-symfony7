<?php

namespace App\Entity;

use App\PandaScore\Enum\WinnerTypeEnum;
use App\Repository\SerieRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\Uid\UuidV7 as Uuid;

#[ORM\Entity(repositoryClass: SerieRepository::class)]
class Serie extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(unique: true)]
    private int $pandaScoreId ;

    #[ORM\Column(length: 127)]
    private ?string $name;

    #[ORM\Column(length: 127, unique: true)]
    #[Slug(fields: ['name'])]
    private ?string $slug ;

    #[ORM\Column]
    private bool $active;

    #[ORM\Column(nullable: true)]
    private ?int $winnerId;

    #[ORM\Column(type: "string", enumType: WinnerTypeEnum::class)]
    private ?WinnerTypeEnum $winnerType;

    #[ORM\Column]
    private ?DateTimeImmutable $beginAt;

    #[ORM\Column]
    private ?DateTimeImmutable $endAt;

    #[ORM\ManyToOne(targetEntity: League::class, inversedBy: 'id')]
    private League $league;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'id')]
    private Game $game;

    /**
     * @param int $pandaScoreId
     * @param string|null $name
     * @param string|null $slug
     * @param bool $active
     * @param int|null $winnerId
     * @param WinnerTypeEnum|null $winnerType
     * @param DateTimeImmutable|null $beginAt
     * @param DateTimeImmutable|null $endAt
     * @param League $league
     * @param Game $game
     */
    public function __construct(int $pandaScoreId, ?string $name, ?string $slug, bool $active, ?int $winnerId, ?WinnerTypeEnum $winnerType, ?DateTimeImmutable $beginAt, ?DateTimeImmutable $endAt, League $league, Game $game)
    {
        $this->pandaScoreId = $pandaScoreId;
        $this->name = $name;
        $this->slug = $slug;
        $this->active = $active;
        $this->beginAt = $beginAt;
        $this->endAt = $endAt;
        $this->league = $league;
        $this->game = $game;
        $this->winnerId = $winnerId;
        $this->winnerType = $winnerType;
    }


    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPandaScoreId(): int
    {
        return $this->pandaScoreId;
    }

    public function setPandaScoreId(int $pandaScoreId): static
    {
        $this->pandaScoreId = $pandaScoreId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getBeginAt(): ?DateTimeImmutable
    {
        return $this->beginAt;
    }

    public function setBeginAt(?DateTimeImmutable $beginAt): void
    {
        $this->beginAt = $beginAt;
    }

    public function getEndAt(): ?DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?DateTimeImmutable $endAt): void
    {
        $this->endAt = $endAt;
    }

    public function getLeague(): League
    {
        return $this->league;
    }

    public function setLeague(League $league): void
    {
        $this->league = $league;
    }

    public function getWinnerId(): ?int
    {
        return $this->winnerId;
    }

    public function setWinnerId(?int $winnerId):void
    {
        $this->winnerId = $winnerId;
    }

    public function getWinnerType(): ?WinnerTypeEnum
    {
        return $this->winnerType;
    }

    public function setWinnerType(?WinnerTypeEnum $winnerType):void
    {
        $this->winnerType = $winnerType;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game):void
    {
        $this->game = $game;
    }
}
