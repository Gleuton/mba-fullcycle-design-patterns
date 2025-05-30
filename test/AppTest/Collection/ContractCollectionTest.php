<?php

namespace AppTest\Collection;

use App\Domain\Collection\ContractCollection;
use App\Domain\Contract;
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
