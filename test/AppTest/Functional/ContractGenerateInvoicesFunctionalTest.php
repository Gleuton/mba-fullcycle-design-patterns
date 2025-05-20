<?php

namespace AppTest\Functional;

use App\Entity\Contract;
use App\Entity\Invoice;
use App\Entity\Payment;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class ContractGenerateInvoicesFunctionalTest extends TestCase
{
    private Contract $contract;

    protected function setUp(): void
    {
        $this->contract = new Contract();
        $this->contract->setDescription('Functional Test Contract');
        $this->contract->setAmount(1200.00);
        $this->contract->setPeriods(12);
        $this->contract->setDate(new DateTimeImmutable('2023-01-01'));

        $this->addPayment('2023-01-15', 100.00);
        $this->addPayment('2023-02-15', 200.00);
        $this->addPayment('2023-03-15', 300.00);
    }

    private function addPayment(string $date, float $amount): void
    {
        $payment = new Payment();
        $payment->setAmount($amount);
        $payment->setContract($this->contract);

        $reflectionPayment = new \ReflectionClass($payment);
        $dateProperty = $reflectionPayment->getProperty('date');
        $dateProperty->setValue($payment, new DateTimeImmutable($date));

        $this->contract->addPayment($payment);
    }

    #[Test]
    #[TestDox('Deve gerar faturas pelo regime de caixa para um mês específico')]
    public function testGenerateInvoicesCashForSpecificMonth(): void
    {
        $invoices = $this->contract->generateInvoices(2, 2023, 'cash');

        $this->assertCount(1, $invoices);
        $this->assertInstanceOf(Invoice::class, $invoices[0]);
        $this->assertEquals(200.00, $invoices[0]->getAmount());
        $this->assertEquals('2023-02-15', $invoices[0]->getDate()->format('Y-m-d'));
    }

    #[Test]
    #[TestDox('Deve gerar faturas pelo regime de competência para um mês específico')]
    public function testGenerateInvoicesAccrualForSpecificMonth(): void
    {
        $invoices = $this->contract->generateInvoices(3, 2023, 'accrual');

        $this->assertCount(1, $invoices);
        $this->assertInstanceOf(Invoice::class, $invoices[0]);
        $this->assertEquals(1200.00 / 12, $invoices[0]->getAmount());
    }

    #[Test]
    #[TestDox('Deve retornar array vazio quando não há pagamentos no mês especificado (regime de caixa)')]
    public function testGenerateInvoicesCashForMonthWithNoPayments(): void
    {
        $invoices = $this->contract->generateInvoices(4, 2023, 'cash');

        $this->assertCount(0, $invoices);
    }

    #[Test]
    #[TestDox('Deve gerar faturas para todos os meses no período do contrato (regime de competência)')]
    public function testGenerateInvoicesAccrualForAllMonths(): void
    {
        $invoiceCount = 0;
        $expectedAmount = 1200.00 / 12;

        for ($month = 1; $month <= 12; $month++) {
            $invoices = $this->contract->generateInvoices($month, 2023, 'accrual');

            $this->assertCount(1, $invoices);
            $invoiceCount++;

            $this->assertEquals($expectedAmount, $invoices[0]->getAmount());
        }

        $this->assertEquals(12, $invoiceCount);
    }
}
