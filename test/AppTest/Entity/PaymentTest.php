<?php

namespace AppTest\Entity;

use App\Infra\Entity\Contract;
use App\Infra\Entity\Payment;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    #[Test]
    #[TestDox('Deve criar um pagamento com ID e data corretos')]
    public function testCreatePayment(): void
    {
        $payment = new Payment();
        
        $this->assertNotEmpty($payment->getId());
        $this->assertInstanceOf(DateTimeInterface::class, $payment->getDate());
    }
    
    #[Test]
    #[TestDox('Deve definir e obter o contrato corretamente')]
    public function testSetAndGetContract(): void
    {
        $contract = $this->createMock(Contract::class);
        $payment = new Payment();
        
        $payment->setContract($contract);
        
        $this->assertSame($contract, $payment->getContract());
    }
    
    #[Test]
    #[TestDox('Deve definir e obter o valor corretamente')]
    public function testSetAndGetAmount(): void
    {
        $amount = 750.25;
        $payment = new Payment();
        
        $payment->setAmount($amount);
        
        $this->assertSame($amount, $payment->getAmount());
    }
    
    #[Test]
    #[TestDox('Deve converter para array corretamente')]
    public function testToArray(): void
    {
        $payment = new Payment();
        $payment->setAmount(500.00);
        
        $array = $payment->toArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('id_payment', $array);
        $this->assertArrayHasKey('amount', $array);
        $this->assertArrayHasKey('date', $array);
        $this->assertSame(500.00, $array['amount']);
    }
}