<?php

namespace App\Domain;

use App\Application\InvoiceGenerationStrategy;
use InvalidArgumentException;

class InvoiceGenerationFactory
{
    public function generate(string $type): InvoiceGenerationStrategy
    {
        return match ($type) {
            'cash' => new CashBasisStrategy(),
            'accrual' => new AccrualBasisStrategy(),
            default => throw new InvalidArgumentException('Invalid invoice generation strategy type'),
        };
    }
}