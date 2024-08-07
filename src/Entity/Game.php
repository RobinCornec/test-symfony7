<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\Uid\UuidV7 as Uuid;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(unique: true)]
    private int $pandaScoreId;

    #[ORM\Column(length: 127)]
    private ?string $name;

    #[ORM\Column(length: 127, unique: true)]
    #[Slug(fields: ['name'])]
    private ?string $slug;

    #[ORM\Column]
    private bool $active;

    /**
     * @var Collection<int, League>
     */
    #[ORM\OneToMany(mappedBy: 'game', targetEntity: League::class)]
    private Collection $leagues;

    /**
     * @var Collection<int,Serie>
     */
    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Serie::class)]
    private Collection $series;

    /**
     * @param int $pandaScoreId
     * @param string|null $name
     * @param string|null $slug
     * @param bool $active
     */
    public function __construct(int $pandaScoreId, ?string $name = null, ?string $slug = null, bool $active = false)
    {
        $this->pandaScoreId = $pandaScoreId;
        $this->name = $name;
        $this->slug = $slug;
        $this->active = $active;
        $this->leagues = new ArrayCollection();
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

    /**
     * @return Collection<int, League>
     */
    public  function getLeagues(): Collection
    {
        return $this->leagues;
    }

    /**
     * @return Collection<int, Serie>
     */
    public  function getSeries(): Collection
    {
        return $this->series;
    }

}
