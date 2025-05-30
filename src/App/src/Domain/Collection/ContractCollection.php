<?php

declare(strict_types=1);

namespace App\Collection;

use App\Domain\Contract;
use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;

class ContractCollection extends ArrayCollection
{
    public function __construct(array $contracts = [])
    {
        foreach ($contracts as $contract) {
            if (!$contract instanceof Contract) {
                throw new InvalidArgumentException('All items in ContractCollection must be Contract objects');
            }
        }
        parent::__construct($contracts);
    }

    public function addContract(Contract $contract): bool
    {
        $this->add($contract);
        return true;
    }

    public function setContract(string $key, Contract $contract): void
    {
        $this->set($key, $contract);
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this as $contract) {
            $result[] = $contract;
        }
        return $result;
    }
}
