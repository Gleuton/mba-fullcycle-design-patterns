<?php

namespace App\Domain\Collection;

use App\Application\InvoiceGenerationStrategy;
use App\Domain\Contract;
use App\Domain\Payment;
use DateMalformedStringException;
use DateTimeImmutable;

class ContractCollectionFactory
{
    /**
     * @throws DateMalformedStringException
     */
    public function generate(
        array $contractsEntities,
        InvoiceGenerationStrategy $generationStrategy
    ): ContractCollection {
        $contracts = [];
        foreach ($contractsEntities as $contractsEntity) {
            $paymentCollection = new PaymentCollection();
            foreach ($contractsEntity->getPayments() as $paymentEntity) {
                $payment = new Payment(
                    $paymentEntity->getId(),
                    $paymentEntity->getAmount(),
                    $paymentEntity->getDate()
                );
                $paymentCollection->addPayment($payment);
            }

            $contracts[] = new Contract(
                $contractsEntity->getId(),
                $contractsEntity->getDescription() ?? '',
                $contractsEntity->getAmount(),
                $contractsEntity->getPeriods(),
                new DateTimeImmutable($contractsEntity->getDate()),
                $paymentCollection,
                $generationStrategy
            );
        }
        return new ContractCollection($contracts);
    }
}