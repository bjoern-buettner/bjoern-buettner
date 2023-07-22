<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Entity;

use Me\BjoernBuettner\Entity;

class PostKeyword extends Entity
{
    public function __construct(?int $aid, private readonly int $keyword, private readonly int $post)
    {
        parent::__construct($aid);
    }
    public function getKeyword(): int
    {
        return $this->keyword;
    }
    public function getPost(): int
    {
        return $this->post;
    }
}
