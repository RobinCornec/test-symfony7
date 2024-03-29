<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\UX\Turbo\Attribute\Broadcast;
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
    private ?int $pandaScoreId = null;

    #[ORM\Column(length: 31)]
    private ?string $name = null;

    #[ORM\Column(length: 31, unique: true)]
    #[Slug(fields: ['name'])]
    private ?string $slug = null;

    #[ORM\Column()]
    private bool $active = false;

    /**
     * @param int|null $pandaScoreId
     * @param string|null $name
     * @param string|null $slug
     * @param bool $active
     */
    public function __construct(?int $pandaScoreId = null, ?string $name = null, ?string $slug = null, bool $active = false)
    {
        $this->pandaScoreId = $pandaScoreId;
        $this->name = $name;
        $this->slug = $slug;
        $this->active = $active;
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

    public function getPandaScoreId(): ?int
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

}
