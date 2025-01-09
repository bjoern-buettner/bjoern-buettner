<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Entity;

use Me\BjoernBuettner\ObjectRelationshipMapping\Entity;

class Task extends Entity
{
    protected int $category = 0;
    protected string $feeType = '';
    protected float $price = 0.0;
    protected string $enName = '';
    protected string $deName = '';
    protected string $deDescription = '';
    protected string $enDescription = '';

    public function __construct(
        ?int $aid,
        int $category,
        string $feeType,
        float $price,
        string $enName,
        string $deName,
        string $enDescription,
        string $deDescription,
    ) {
        $this->enDescription = $enDescription;
        $this->deDescription = $deDescription;
        $this->deName = $deName;
        $this->enName = $enName;
        $this->price = $price;
        $this->feeType = $feeType;
        $this->category = $category;
        parent::__construct($aid);
    }
}
