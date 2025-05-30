<?php

namespace App\Domain;

use DateTimeInterface;

class Invoice
{
    public function __construct(
        private readonly DateTimeInterface $date,
        private readonly float $amount,
    ) {
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