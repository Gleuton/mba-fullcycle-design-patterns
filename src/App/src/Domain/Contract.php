<?php

namespace App\Domain;

use App\Application\InvoiceGenerationStrategy;
use App\Domain\Collection\InvoiceCollection;
use App\Domain\Collection\PaymentCollection;
use DateTimeInterface;

class Contract
{
    public function __construct(
        private readonly string $idContract,
        private readonly string $description,
        private readonly float $amount,
        private readonly int $periods,
        private readonly DateTimeInterface $date,
        private PaymentCollection $payments,
        private InvoiceGenerationStrategy $generationStrategy,
    ) {
    }

    public function getId(): string
    {
        return $this->idContract;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getPeriods(): int
    {
        return $this->periods;
    }

    public function addPayment(Payment $payment): void
    {
        $this->payments[] = $payment;
    }

    public function getPayments(): PaymentCollection
    {
        return $this->payments;
    }

    public function generateInvoices(int $month, int $year): InvoiceCollection
    {
        return $this->generationStrategy->generate($this, $month, $year);
    }
}