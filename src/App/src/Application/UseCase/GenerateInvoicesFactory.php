<?php

namespace App\Application\UseCase;

use App\Application\Decorator\LoggerDecorator;
use App\Domain\Collection\ContractCollectionFactory;
use App\Domain\InvoiceGenerationFactory;
use App\Infra\Repository\ContractRepository;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class GenerateInvoicesFactory
{
    public function __invoke(ContainerInterface $container): GenerateInvoicesInterface
    {
        $generateInvoices = new GenerateInvoices(
            $container->get(ContractRepository::class),
            $container->get(ContractCollectionFactory::class),
            $container->get(InvoiceGenerationFactory::class)
        );

        return new LoggerDecorator(
            $generateInvoices,
            $container->get(LoggerInterface::class),
        );
    }
}