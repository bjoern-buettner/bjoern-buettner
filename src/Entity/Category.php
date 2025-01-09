<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Entity;

use Me\BjoernBuettner\ObjectRelationshipMapping\Entity;

class Category extends Entity
{
    private string $en = '';
    private string $de = '';

    public function __construct(
        ?int $aid,
        string $en,
        string $de,
    ) {
        $this->de = $de;
        $this->en = $en;
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
