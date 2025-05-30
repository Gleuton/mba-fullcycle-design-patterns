<?php

namespace App\Domain;

use DateTimeInterface;

class Payment
{
    public function __construct(
        private readonly string $idPayment,
        private readonly float $amount,
        private readonly DateTimeInterface $date
    )
    {
    }

    public function getId(): string
    {
        return $this->idPayment;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}