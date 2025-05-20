<?php

namespace App\Collection;

use App\Entity\Contract;

interface ContractCollectionInterface
{
    public function addContract(Contract $contract): bool;
    public function setContract(string $key, Contract $contract): void;

    public function toArray(): array;
}