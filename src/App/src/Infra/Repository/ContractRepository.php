<?php

declare(strict_types=1);

namespace App;

use App\Entity\Contract as ContractEntity;
use Doctrine\ORM\EntityManager;

class ContractRepository
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    public function list(): array
    {
        return $this->entityManager->getRepository(ContractEntity::class)->findAll();
    }
}
