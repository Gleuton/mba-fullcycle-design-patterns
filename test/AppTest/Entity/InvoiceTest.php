<?php

namespace AppTest\Entity;

use App\Infra\Entity\Invoice;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    #[Test]
    #[TestDox('Deve criar uma fatura com data e valor corretos')]
    public function testCreateInvoiceWithCorrectDateAndAmount(): void
    {
        $date = new DateTimeImmutable('2023-05-01');
        $amount = 100.50;
        
        $invoice = new Invoice($date, $amount);
        
        $this->assertSame($date, $invoice->getDate());
        $this->assertSame($amount, $invoice->getAmount());
    }
    
    #[Test]
    #[TestDox('Deve retornar o valor correto da fatura')]
    public function testGetAmount(): void
    {
        $date = new DateTimeImmutable();
        $amount = 250.75;
        
        $invoice = new Invoice($date, $amount);
        
        $this->assertSame($amount, $invoice->getAmount());
    }
    
    #[Test]
    #[TestDox('Deve retornar a data correta da fatura')]
    public function testGetDate(): void
    {
        $date = new DateTimeImmutable('2023-06-15');
        $amount = 500.00;
        
        $invoice = new Invoice($date, $amount);
        
        $this->assertSame($date, $invoice->getDate());
    }
}