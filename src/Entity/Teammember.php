<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Entity;

use Me\BjoernBuettner\ObjectRelationshipMapping\Entity;

class Teammember extends Entity
{
    public function __construct(
        ?int $aid,
        private readonly string $name,
        private readonly string $descriptionEn,
        private readonly string $descriptionDe,
        private readonly string $github,
        private readonly string $linkedin,
        private readonly string $slug,
    ) {
        parent::__construct($aid);
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getDescriptionEn(): string
    {
        return $this->descriptionEn;
    }
    public function getDescriptionDe(): string
    {
        return $this->descriptionDe;
    }
    public function getGithub(): string
    {
        return $this->github;
    }
    public function getLinkedin(): string
    {
        return $this->linkedin;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }
}
