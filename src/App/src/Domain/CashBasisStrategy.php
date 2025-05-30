<?php

namespace App\Domain;

use App\Application\InvoiceGenerationStrategy;
use App\Domain\Collection\InvoiceCollection;

class CashBasisStrategy implements InvoiceGenerationStrategy
{
    public function generate(Contract $contract, int $month, int $year): InvoiceCollection
    {
        $invoices = new InvoiceCollection();
        $payments = $contract->getPayments();
        /**
         * @var Payment $payment
         */
        foreach ($payments as $payment) {
            if ((int) $payment->getDate()->format('n') === $month && (int) $payment->getDate()->format('Y') === $year) {
                $invoices->addInvoice(new Invoice($payment->getDate(), $payment->getAmount()));
            }
        }
        return $invoices;
    }
}