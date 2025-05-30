<?php

namespace AppTest;

use App\Infra\Entity\Contract;
use App\Infra\Repository\ContractRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class ContractRepositoryTest extends TestCase
{
    private EntityRepository $doctrineRepository;
    private ContractRepository $repository;
    
    protected function setUp(): void
    {
        $entityManager = $this->createMock(EntityManager::class);
        $this->doctrineRepository = $this->createMock(EntityRepository::class);
        
        $entityManager
            ->method('getRepository')
            ->with(Contract::class)
            ->willReturn($this->doctrineRepository);
            
        $this->repository = new ContractRepository($entityManager);
    }
    
    #[Test]
    #[TestDox('Deve listar todos os contratos')]
    public function testList(): void
    {
        $contract1 = $this->createMock(Contract::class);
        $contract2 = $this->createMock(Contract::class);
        
        $contracts = [$contract1, $contract2];
        
        $this->doctrineRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($contracts);
        
        $result = $this->repository->list();

        $this->assertCount(2, $result);
    }
    
    #[Test]
    #[TestDox('Deve retornar uma coleção vazia quando não há contratos')]
    public function testListWithNoContracts(): void
    {
        $this->doctrineRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        
        $result = $this->repository->list();

        $this->assertEmpty($result);
    }
}