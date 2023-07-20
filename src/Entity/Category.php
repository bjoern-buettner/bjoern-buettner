<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Entity;

use Me\BjoernBuettner\Entity;

class Category extends Entity
{
    public function __construct(
        ?int $aid,
        private readonly string $en,
        private readonly string $de,
    ) {
        parent::__construct($aid);
    }
    public function getEn(): string
    {
        return $this->en;
    }
    public function getDe(): string
    {
        return $this->de;
    }
}
