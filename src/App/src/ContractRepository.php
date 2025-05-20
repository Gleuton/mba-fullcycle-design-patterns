<?php

declare(strict_types=1);

namespace App;

use App\Collection\ContractCollection;
use App\Collection\ContractCollectionInterface;
use App\Entity\Contract;
use Doctrine\ORM\EntityManager;
use Override;

class ContractRepository
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    public function list(): ContractCollectionInterface
    {
        $contracts = $this->entityManager->getRepository(Contract::class)->findAll();
        return new ContractCollection($contracts);
    }
}
