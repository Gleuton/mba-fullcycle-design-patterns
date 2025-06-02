<?php

namespace AppTest;

use App\Application\Presenter\PresenterInterface;
use App\Application\Usecase\GenerateInvoices;
use App\Domain\CashBasisStrategy;
use App\Domain\Collection\ContractCollection;
use App\Domain\Collection\ContractCollectionFactory;
use App\Domain\Collection\InvoiceCollection;
use App\Domain\Contract;
use App\Domain\Invoice;
use App\Domain\InvoiceGenerationFactory;
use App\Infra\Repository\ContractRepository;
use Override;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GenerateInvoicesTest extends TestCase
{
    private ContractRepository|MockObject $contractRepository;
    private ContractCollectionFactory|MockObject $contractCollectionFactory;
    private InvoiceGenerationFactory|MockObject $invoiceGenerationFactory;

    #[Override]
    protected function setUp(): void
    {
        $this->contractRepository = $this->createMock(ContractRepository::class);
        $this->contractCollectionFactory = $this->createMock(ContractCollectionFactory::class);
        $this->invoiceGenerationFactory = $this->createMock(InvoiceGenerationFactory::class);
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

        $invoice = $this->createMock(Invoice::class);
        $invoice->method('getDate')->willReturn(new \DateTimeImmutable('2023-02-01'));
        $invoice->method('getAmount')->willReturn(6000.0);

        $contract = $this->createMock(Contract::class);
        $mockInvoiceCollection = new InvoiceCollection([$invoice]);
        $contract->method('generateInvoices')->willReturn($mockInvoiceCollection);

        $this->contractRepository->method('list')->willReturn([$contract]);

        $cashBasisStrategy = $this->createMock(CashBasisStrategy::class);
        $cashBasisStrategy->method('generate')->willReturn($mockInvoiceCollection);

        $mockContractCollection = new ContractCollection([$contract]);

        $this->invoiceGenerationFactory
            ->method('generate')
            ->with('cash')
            ->willReturn($cashBasisStrategy);

        $this->contractCollectionFactory
            ->method('generate')
            ->with([$contract], $this->anything())
            ->willReturn($mockContractCollection);

        $generateInvoice = new GenerateInvoices(
            $this->contractRepository,
            $this->contractCollectionFactory,
            $this->invoiceGenerationFactory
        );
        $expectedOutput = [
            'date' => new \DateTimeImmutable('2023-02-01'),
            'amount' => 6000.0
        ];

        $presenter = $this->createMock(PresenterInterface::class);

        $presenter->expects($this->once())->method('present')->with([$expectedOutput])->willReturn([$expectedOutput]);

        $output = $generateInvoice->generateInvoice((int)$input['month'], (int)$input['year'], $input['type'], $presenter);
        Assert::assertSame($expectedOutput['date'], $output[0]['date']);
        Assert::assertSame($expectedOutput['amount'], $output[0]['amount']);
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

        $invoice = $this->createMock(Invoice::class);
        $invoice->method('getDate')->willReturn(new \DateTimeImmutable('2023-02-01'));
        $invoice->method('getAmount')->willReturn(500.0);

        $contract = $this->createMock(Contract::class);
        $mockInvoiceCollection = new InvoiceCollection([$invoice]);
        $contract->method('generateInvoices')->willReturn($mockInvoiceCollection);

        $this->contractRepository->method('list')->willReturn([$contract]);

        $mockContractCollection = new ContractCollection([$contract]);
        $this->contractCollectionFactory
            ->method('generate')
            ->willReturn($mockContractCollection);

        $accrualStrategy = $this->createMock(\App\Domain\AccrualBasisStrategy::class);
        $accrualStrategy->method('generate')->willReturn($mockInvoiceCollection);

        $this->invoiceGenerationFactory
            ->method('generate')
            ->with('accrual')
            ->willReturn($accrualStrategy);

        $presenter = $this->createMock(PresenterInterface::class);
        $expectedOutput = [
            'date' => '2023-02-01',
            'amount' => 500.0
        ];
        $presenter->method('present')->willReturn([$expectedOutput]);

        $generateInvoice = new GenerateInvoices(
            $this->contractRepository,
            $this->contractCollectionFactory,
            $this->invoiceGenerationFactory
        );

        $output = $generateInvoice->generateInvoice(
            (int)$input['month'],
            (int)$input['year'],
            $input['type'],
            $presenter
        );

        Assert::assertSame($expectedOutput['date'], $output[0]['date']);
        Assert::assertSame($expectedOutput['amount'], $output[0]['amount']);
    }
}
