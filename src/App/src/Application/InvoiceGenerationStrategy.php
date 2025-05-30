<?php

namespace App\Application;

use App\Domain\Collection\InvoiceCollection;
use App\Domain\Contract;

interface InvoiceGenerationStrategy
{
    public function generate(Contract $contract, int $month, int $year): InvoiceCollection;
}