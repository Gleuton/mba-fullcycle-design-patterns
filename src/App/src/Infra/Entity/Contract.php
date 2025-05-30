<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: "contract", schema: "mba")]
class Contract
{
    #[ORM\Id]
    #[ORM\Column(type: "guid", unique: true)]
    private string $id_contract;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private float $amount;

    #[ORM\Column(type: "integer")]
    private int $periods;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $date;

    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: "contract")]
    private Collection $payments;

    public function __construct()
    {
        $this->id_contract = Uuid::uuid4()->toString();
        $this->date = new DateTimeImmutable();
        $this->payments = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id_contract;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getPeriods(): int
    {
        return $this->periods;
    }

    public function getDate(): string
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Collection
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function setPeriods(int $periods): void
    {
        $this->periods = $periods;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setContract($this);
        }

        return $this;
    }

    public function toArray(): array
    {
        $paymentsArray = [];
        foreach ($this->payments as $payment) {
            $paymentsArray[] = $payment->toArray();
        }

        return [
            'id_contract' => $this->getId(),
            'description' => $this->getDescription(),
            'amount' => $this->getAmount(),
            'periods' => $this->getPeriods(),
            'date' => $this->getDate(),
            'payments' => $paymentsArray,
        ];
    }
}
