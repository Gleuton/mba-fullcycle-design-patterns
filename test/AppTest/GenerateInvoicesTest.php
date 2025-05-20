<?php

namespace AppTest;

use App\Collection\ContractCollection;
use App\ContractRepository;
use App\Entity\Contract;
use App\GenerateInvoices;
use Override;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GenerateInvoicesTest extends TestCase
{
    private ContractRepository|MockObject $contractRepository;

    #[Override]
    protected function setUp(): void
    {
        $this->contractRepository = $this->createMock(ContractRepository::class);
    }

    #[Test]
    #[TestDox('Deve Gerar notas fiscais por regime de caixa')]
    public function testGenerateInvoiceForTypeCash(): void
    {
        $input = [
            'month' => '02',
            'year' => '2023',
            'type' => 'cash'
        ];

        $invoice = $this->createMock(\App\Entity\Invoice::class);
        $invoice->method('getDate')->willReturn(new \DateTimeImmutable('2023-02-01'));
        $invoice->method('getAmount')->willReturn(6000.0);

        $contract = $this->createMock(Contract::class);
        $contract->method('generateInvoices')->willReturn([$invoice]);

        $mockCollection = new ContractCollection([$contract]);
        $this->contractRepository->method('list')->willReturn($mockCollection);

        $generateInvoice = new GenerateInvoices($this->contractRepository);
        $output = $generateInvoice->generateInvoice((int)$input['month'], (int)$input['year'], $input['type']);
        Assert::assertSame('2023-02-01', $output[0]['date']);
        Assert::assertSame(6000.0, $output[0]['amount']);
    }

    #[Test]
    #[TestDox('Deve Gerar notas fiscais por regime de Competência')]
    public function testGenerateInvoiceForTypeAccrual(): void
    {
        $input = [
            'month' => '02',
            'year' => '2023',
            'type' => 'accrual'
        ];

        $invoice = $this->createMock(\App\Entity\Invoice::class);
        $invoice->method('getDate')->willReturn(new \DateTimeImmutable('2023-02-01'));
        $invoice->method('getAmount')->willReturn(500.0);

        $contract = $this->createMock(Contract::class);
        $contract->method('generateInvoices')->willReturn([$invoice]);

        $mockCollection = new ContractCollection([$contract]);
        $this->contractRepository->method('list')->willReturn($mockCollection);

        $generateInvoice = new GenerateInvoices($this->contractRepository);
        $output = $generateInvoice->generateInvoice((int)$input['month'], (int)$input['year'], $input['type']);
        Assert::assertSame('2023-02-01', $output[0]['date']);
        Assert::assertSame(500.0, $output[0]['amount']);
    }
}
