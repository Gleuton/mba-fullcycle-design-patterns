<?php

namespace App\Domain;

use App\Application\InvoiceGenerationStrategy;
use App\Domain\Collection\InvoiceCollection;
use DateInterval;
use DateMalformedIntervalStringException;

class AccrualBasisStrategy implements InvoiceGenerationStrategy
{

    /**
     * @throws DateMalformedIntervalStringException
     */
    public function generate(Contract $contract, int $month, int $year): InvoiceCollection
    {
        $invoices = new InvoiceCollection();
        $date = $contract->getDate();
        $periods = $contract->getPeriods();
        $contractAmount = $contract->getAmount();

        for ($period = 0; $period <= $periods; $period++) {
            $startDate = clone $date;
            $currentDate = $startDate->add(new DateInterval("P{$period}M"));
            $currentMonth = (int) $currentDate->format('n');
            $currentYear = (int) $currentDate->format('Y');

            if ($currentMonth === $month && $currentYear === $year) {
                $amount = $contractAmount / $periods;
                $invoices->addInvoice(new Invoice($currentDate, $amount));
            }
        }
        return $invoices;
    }
}