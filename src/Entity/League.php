<?php

namespace App\Entity;

use App\Repository\LeagueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\Uid\UuidV7 as Uuid;

#[ORM\Entity(repositoryClass: LeagueRepository::class)]
class League extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(unique: true)]
    private int $pandaScoreId;

    #[ORM\Column(length: 127)]
    private ?string $name = null;

    #[ORM\Column(length: 31, unique: true)]
    #[Slug(fields: ['name'])]
    private ?string $slug = null;

    #[ORM\Column()]
    private bool $active = false;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'id')]
    private Game $game;

    /**
     * @var Collection<int, Serie>
     */
    #[ORM\OneToMany(mappedBy: 'league', targetEntity: Serie::class)]
    private Collection $series;

    /**
     * @param int $pandaScoreId
     * @param string|null $name
     * @param string|null $slug
     * @param bool $active
     * @param Game $game
     */
    public function __construct(int $pandaScoreId, ?string $name, ?string $slug, bool $active, Game $game)
    {
        $this->pandaScoreId = $pandaScoreId;
        $this->name = $name;
        $this->slug = $slug;
        $this->active = $active;
        $this->game = $game;
        $this->series = new ArrayCollection();
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

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    /**
     * @return Collection<int, Serie>
     */
    public function getSeries(): Collection
    {
        return $this->series;
    }
}
