<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Payment;
use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;

class PaymentCollection extends ArrayCollection
{
    public function __construct(array $payments = [])
    {
        foreach ($payments as $payment) {
            if (!$payment instanceof Payment) {
                throw new InvalidArgumentException('All items in PaymentCollection must be Payment objects');
            }
        }
        parent::__construct($payments);
    }

    public function addPayment(Payment $payment): bool
    {
        $this->add($payment);
        return true;
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this as $payment) {
            $result[] = $payment;
        }
        return $result;
    }
}
