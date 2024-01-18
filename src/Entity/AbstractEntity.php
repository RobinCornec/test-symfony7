<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

class AbstractEntity
{
    #[ORM\Column()]
    #[Timestampable(on: "create")]
    protected DateTime $createdAt;

    #[ORM\Column()]
    #[Timestampable(on: "update")]
    protected DateTime $updatedAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}