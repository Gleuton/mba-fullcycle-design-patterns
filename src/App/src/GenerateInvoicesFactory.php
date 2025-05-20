<?php

declare(strict_types=1);

namespace App;

use Psr\Container\ContainerInterface;

class GenerateInvoicesFactory
{
    public function __invoke(ContainerInterface $container): GenerateInvoices
    {
        $contractRepository = $container->get(ContractRepository::class);
        return new GenerateInvoices($contractRepository);
    }
}