<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Entity;

use DateTimeImmutable;
use Me\BjoernBuettner\ObjectRelationshipMapping\Entity;

class Post extends Entity
{
    public function __construct(
        ?int $aid,
        private readonly string $contentEn,
        private readonly string $contentDe,
        private readonly string $titleEn,
        private readonly string $titleDe,
        private readonly string $extractEn,
        private readonly string $extractDe,
        private readonly string $slug,
        private readonly DateTimeImmutable $created,
        private readonly string $author,
    ) {
        parent::__construct($aid);
    }
    public function getContentEn(): string
    {
        return $this->contentEn;
    }
    public function getContentDe(): string
    {
        return $this->contentDe;
    }
    public function getTitleEn(): string
    {
        return $this->titleEn;
    }
    public function getTitleDe(): string
    {
        return $this->titleDe;
    }
    public function getExtractEn(): string
    {
        return $this->extractEn;
    }
    public function getExtractDe(): string
    {
        return $this->extractDe;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }
    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }
    public function getAuthor(): string
    {
        return $this->author;
    }
}
