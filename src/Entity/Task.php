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
    private string $paypalButtonCode = '';

    public function __construct(
        ?int $aid,
        int $category,
        string $feeType,
        float $price,
        string $enName,
        string $deName,
        string $enDescription,
        string $deDescription,
        string $paypalButtonCode
    ) {
        $this->enDescription = $enDescription;
        $this->deDescription = $deDescription;
        $this->deName = $deName;
        $this->enName = $enName;
        $this->price = $price;
        $this->feeType = $feeType;
        $this->category = $category;
        $this->paypalButtonCode = $paypalButtonCode;
        parent::__construct($aid);
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function getFeeType(): string
    {
        return $this->feeType;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getEnName(): string
    {
        return $this->enName;
    }

    public function getDeName(): string
    {
        return $this->deName;
    }

    public function getDeDescription(): string
    {
        return $this->deDescription;
    }

    public function getEnDescription(): string
    {
        return $this->enDescription;
    }

    public function getPaypalButtonCode(): string
    {
        return $this->paypalButtonCode;
    }
}
