<?php

namespace App;

use App\Domain\Collection\ContractCollectionFactory;
use App\Domain\Contract;
use App\Domain\Invoice;

class GenerateInvoices
{
    public function __construct(
        private ContractRepository $contractRepository,
        private ContractCollectionFactory $collectionFactory,
        private InvoiceGenerationFactory $invoiceGenerationFactory,
    )
    {
    }

    public function generateInvoice(int $month, int $year, string $type, PresenterInterface $presenter): mixed
    {
        $contractsList = $this->contractRepository->list();
        $invoiceGeneration = $this->invoiceGenerationFactory->generate($type);
        $contracts = $this->collectionFactory->generate($contractsList, $invoiceGeneration);

        $output = [];
        /**
         * @var Contract $contract
         */
        foreach ($contracts as $contract) {
            $invoices = $contract->generateInvoices($month, $year);
            /**
             * @var Invoice $invoice
             */
            foreach ($invoices as $invoice) {
                $output[] = [
                    'date' => $invoice->getDate(),
                    'amount' => $invoice->getAmount()
                ];
            }
        }

        return $presenter->present($output);
    }
}
