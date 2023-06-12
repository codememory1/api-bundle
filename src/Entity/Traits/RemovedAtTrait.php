<?php

namespace Codememory\ApiBundle\Entity\Traits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait RemovedAtTrait
{
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $removedAt = null;

    public function getRemovedAt(): ?DateTimeImmutable
    {
        return $this->removedAt;
    }

    public function setRemovedAt(): self
    {
        $this->removedAt = new DateTimeImmutable();

        return $this;
    }

    public function undoRemovedAt(): self
    {
        $this->removedAt = null;

        return $this;
    }
}