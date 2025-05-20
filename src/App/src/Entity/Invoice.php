<?php

namespace App\Entity;

use DateTimeInterface;

class Invoice
{
    public function __construct(private DateTimeInterface $date, private float $amount)
    {
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