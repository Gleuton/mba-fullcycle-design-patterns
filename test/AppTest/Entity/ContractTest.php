<?php

namespace AppTest\Entity;

use App\Infra\Entity\Contract;
use App\Infra\Entity\Payment;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class ContractTest extends TestCase
{
    #[Test]
    #[TestDox('Deve criar um contrato com valores padrão')]
    public function testCreateContract(): void
    {
        $contract = new Contract();
        
        $this->assertNotEmpty($contract->getId());
        $this->assertNull($contract->getDescription());
        $this->assertIsString($contract->getDate());
    }
    
    #[Test]
    #[TestDox('Deve definir e obter a descrição corretamente')]
    public function testSetAndGetDescription(): void
    {
        $contract = new Contract();
        $description = 'Test Contract Description';
        
        $contract->setDescription($description);
        
        $this->assertSame($description, $contract->getDescription());
    }
    
    #[Test]
    #[TestDox('Deve definir e obter o valor corretamente')]
    public function testSetAndGetAmount(): void
    {
        $contract = new Contract();
        $amount = 1500.75;
        
        $contract->setAmount($amount);
        
        $this->assertSame($amount, $contract->getAmount());
    }
    
    #[Test]
    #[TestDox('Deve definir e obter o número de períodos corretamente')]
    public function testSetAndGetPeriods(): void
    {
        $contract = new Contract();
        $periods = 24;
        
        $contract->setPeriods($periods);
        
        $this->assertSame($periods, $contract->getPeriods());
    }
    
    #[Test]
    #[TestDox('Deve adicionar pagamentos corretamente')]
    public function testAddPayment(): void
    {
        $contract = new Contract();
        $payment = new Payment();
        
        $contract->addPayment($payment);
        
        $this->assertCount(1, $contract->getPayments());
        $this->assertSame($payment, $contract->getPayments()->first());
    }
    
    #[Test]
    #[TestDox('Deve converter para array corretamente')]
    public function testToArray(): void
    {
        $contract = new Contract();
        $contract->setDescription('Test Contract');
        $contract->setAmount(2000.00);
        $contract->setPeriods(10);
        
        $payment = new Payment();
        $payment->setAmount(200.00);
        
        $contract->addPayment($payment);
        
        $array = $contract->toArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('id_contract', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('amount', $array);
        $this->assertArrayHasKey('periods', $array);
        $this->assertArrayHasKey('date', $array);
        $this->assertArrayHasKey('payments', $array);
        
        $this->assertSame('Test Contract', $array['description']);
        $this->assertSame(2000.00, $array['amount']);
        $this->assertSame(10, $array['periods']);
        $this->assertIsArray($array['payments']);
        $this->assertCount(1, $array['payments']);
    }
}