<?php

namespace App;

use App\Entity\Contract;
use App\Entity\Invoice;

class GenerateInvoices
{
    public function __construct(private ContractRepository $contractRepository)
    {
    }

    public function generateInvoice(int $month, int $year, string $type): array
    {
        $contracts = $this->contractRepository->list();
        $output = [];
        /**
         * @var Contract $contract
         */
        foreach ($contracts as $contract) {
            $invoices = $contract->generateInvoices($month, $year, $type);
            /**
             * @var Invoice $invoice
             */
            foreach ($invoices as $invoice) {
                $output[] = [
                    'date' => $invoice->getDate()->format('Y-m-d'),
                    'amount' => $invoice->getAmount()
                ];
            }
        }

        return $output;
    }
}
