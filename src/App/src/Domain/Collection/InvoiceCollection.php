<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Invoice;
use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;

class InvoiceCollection extends ArrayCollection
{
    public function __construct(array $invoices = [])
    {
        foreach ($invoices as $invoice) {
            if (!$invoice instanceof Invoice) {
                throw new InvalidArgumentException('All items in InvoiceCollection must be Invoice objects');
            }
        }
        parent::__construct($invoices);
    }

    public function addInvoice(Invoice $invoice): bool
    {
        $this->add($invoice);
        return true;
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this as $invoice) {
            $result[] = $invoice;
        }
        return $result;
    }
}
