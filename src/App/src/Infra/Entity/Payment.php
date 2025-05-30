<?php

declare(strict_types=1);

namespace App\Infra\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: "payment", schema: "mba")]
class Payment
{
    #[ORM\Id]
    #[ORM\Column(type: "guid", unique: true)]
    private string $id_payment;

    #[ORM\ManyToOne(targetEntity: Contract::class)]
    #[ORM\JoinColumn(name: "id_contract", referencedColumnName: "id_contract")]
    private Contract $contract;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private float $amount;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $date;

    public function __construct()
    {
        $this->id_payment = Uuid::uuid4()->toString();
        $this->date = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id_payment;
    }

    public function getContract(): Contract
    {
        return $this->contract;
    }

    public function setContract(Contract $contract): void
    {
        $this->contract = $contract;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function toArray(): array
    {
        return [
            'id_payment' => $this->getId(),
            'amount' => $this->getAmount(),
            'date' => $this->getDate(),
        ];
    }
}