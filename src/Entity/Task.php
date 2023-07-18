<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Entity;

use Me\BjoernBuettner\Entity;

class Task extends Entity
{
    public function __construct(
        ?int $aid,
        private readonly int $category,
        private readonly string $feeType,
        private readonly float $price,
        private readonly string $enName,
        private readonly string $deName,
        private readonly string $enDescription,
        private readonly string $deDescription,
    ) {
        parent::__construct($aid);
    }
}
