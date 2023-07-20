<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Entity;

use Me\BjoernBuettner\Entity;

class Keyword extends Entity
{
    public function __construct(
        ?int $aid,
        private readonly string $en,
        private readonly string $de,
        private readonly string $slug,
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
    public function getSlug(): string
    {
        return $this->slug;
    }
}
