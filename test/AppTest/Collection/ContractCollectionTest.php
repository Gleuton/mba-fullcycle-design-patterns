<?php

namespace AppTest\Collection;

use App\Collection\ContractCollection;
use App\Entity\Contract;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class ContractCollectionTest extends TestCase
{
    #[Test]
    #[TestDox('Deve aceitar apenas objetos Contract na coleção')]
    public function testOnlyAcceptsContractObjects(): void
    {
        $contract = $this->createMock(Contract::class);

        $collection = new ContractCollection([$contract]);
        $this->assertCount(1, $collection);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('All items in ContractCollection must be Contract objects');
        new ContractCollection(['not a contract']);
    }


    #[Test]
    #[TestDox('Deve converter corretamente para array')]
    public function testCorrectlyConvertsToArray(): void
    {
        $contract = $this->createMock(Contract::class);
        $contract->method('toArray')->willReturn([
            'date' => '2023-02-01',
            'amount' => '500'
        ]);

        $collection = new ContractCollection([$contract]);

        $array = $collection->toArray();
        $this->assertIsArray($array);
        $this->assertCount(1, $array);
        $this->assertSame('2023-02-01', $array[0]['date']);
        $this->assertSame('500', $array[0]['amount']);
    }

    #[Test]
    #[TestDox('Deve adicionar um contrato corretamente')]
    public function testAddContract(): void
    {
        $contract = $this->createMock(Contract::class);
        $collection = new ContractCollection();

        $result = $collection->addContract($contract);

        $this->assertTrue($result);
        $this->assertCount(1, $collection);
        $this->assertSame($contract, $collection->first());
    }

    #[Test]
    #[TestDox('Deve definir um contrato em uma chave específica')]
    public function testSetContract(): void
    {
        $contract1 = $this->createMock(Contract::class);
        $contract2 = $this->createMock(Contract::class);

        $collection = new ContractCollection([$contract1]);
        $this->assertSame($contract1, $collection->first());

        $collection->setContract('0', $contract2);
        $this->assertSame($contract2, $collection->first());
    }
}
