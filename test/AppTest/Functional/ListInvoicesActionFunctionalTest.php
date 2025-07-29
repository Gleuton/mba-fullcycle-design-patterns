<?php

namespace AppTest\Functional;

use App\Action\GenerateInvoiceListInvoicesAction;
use App\Application\Usecase\GenerateInvoices;
use App\Domain\Collection\ContractCollectionFactory;
use App\Domain\InvoiceGenerationFactory;
use App\Infra\Entity\Contract;
use App\Infra\Entity\Payment;
use App\Infra\Repository\ContractRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

class ListInvoicesActionFunctionalTest extends TestCase
{
    private GenerateInvoiceListInvoicesAction $action;
    
    protected function setUp(): void
    {
        $entityManager = $this->createMock(EntityManager::class);
        $contractRepository = new ContractRepository($entityManager);

        $collectionFactory = new ContractCollectionFactory();
        $invoiceGenerationFactory =new InvoiceGenerationFactory();
        $generateInvoices = new GenerateInvoices($contractRepository, $collectionFactory, $invoiceGenerationFactory);
        $this->action = new GenerateInvoiceListInvoicesAction($generateInvoices);
        
        $doctrineRepository = $this->createMock(EntityRepository::class);
        $entityManager
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
        $body = [
            'month' => 3,
            'year' => 2023,
            'type' => 'cash',
        ];

        $request = new ServerRequest(
            serverParams: [],
            uploadedFiles: [],
            uri: '/invoices',
            method: 'POST',
            body: 'php://input',
            headers: ['Content-Type' => 'application/json'],
            cookieParams: [],
            queryParams: [],
            parsedBody: $body
        );

        $response = $this->action->process(
            $request,
            $this->createMock(RequestHandlerInterface::class)
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