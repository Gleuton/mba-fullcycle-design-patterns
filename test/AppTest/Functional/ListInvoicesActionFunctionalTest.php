<?php

namespace AppTest\Functional;

use App\Action\ListInvoicesAction;
use App\ContractRepository;
use App\Entity\Contract;
use App\Entity\Payment;
use App\GenerateInvoices;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class ListInvoicesActionFunctionalTest extends TestCase
{
    private EntityManager $entityManager;
    private ContractRepository $contractRepository;
    private GenerateInvoices $generateInvoices;
    private ListInvoicesAction $action;
    
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->contractRepository = new ContractRepository($this->entityManager);
        $this->generateInvoices = new GenerateInvoices($this->contractRepository);
        $this->action = new ListInvoicesAction($this->generateInvoices);
        
        $doctrineRepository = $this->createMock(\Doctrine\ORM\EntityRepository::class);
        $this->entityManager
            ->method('getRepository')
            ->willReturn($doctrineRepository);
            
        $contract = $this->createContract();
        $doctrineRepository
            ->method('findAll')
            ->willReturn([$contract]);
    }
    
    private function createContract(): Contract
    {
        $contract = new Contract();
        $contract->setDescription('Test Contract');
        $contract->setAmount(1000.00);
        $contract->setPeriods(10);
        
        $payment = new Payment();
        $payment->setAmount(100.00);
        $payment->setContract($contract);
        
        $reflectionPayment = new \ReflectionClass($payment);
        $dateProperty = $reflectionPayment->getProperty('date');
        $dateProperty->setValue($payment, new DateTimeImmutable('2023-03-15'));
        
        $contract->addPayment($payment);
        
        return $contract;
    }
    
    #[Test]
    #[TestDox('Deve processar uma requisição HTTP e retornar faturas no formato JSON')]
    public function testProcessHttpRequest(): void
    {
        $request = new ServerRequest(
            [],
            [],
            '/invoices',
            'GET'
        );
        
        $response = $this->action->process(
            $request,
            $this->createMock(\Psr\Http\Server\RequestHandlerInterface::class)
        );
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        
        $data = json_decode($response->getBody()->getContents(), true);
        
        $this->assertIsArray($data);
        
        if (count($data) > 0) {
            $this->assertArrayHasKey('date', $data[0]);
            $this->assertArrayHasKey('amount', $data[0]);
        }
    }
}