<?php

namespace AppTest\Action;

use App\Action\ListInvoicesAction;
use App\Application\UseCase\GenerateInvoices;
use Laminas\Diactoros\Response\JsonResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ListInvoicesActionTest extends TestCase
{
    private GenerateInvoices $generateInvoices;
    private ListInvoicesAction $action;

    protected function setUp(): void
    {
        $this->generateInvoices = $this->createMock(GenerateInvoices::class);
        $this->action = new ListInvoicesAction($this->generateInvoices);
    }

    #[Test]
    #[TestDox('Deve processar a requisição e retornar uma resposta JSON com as faturas')]
    public function testProcess(): void
    {
        $invoices = [
            ['date' => '2023-03-01', 'amount' => 100.00],
            ['date' => '2023-03-15', 'amount' => 200.00]
        ];

        $this->generateInvoices
            ->expects($this->once())
            ->method('generateInvoice')
            ->with(3, 2023, 'accrual')
            ->willReturn(new JsonResponse($invoices));

        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);

        $request->method('getParsedBody')->willReturn([
            'month' => 3,
            'year' => 2023,
            'type' => 'accrual'
        ]);

        $response = $this->action->process($request, $handler);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($invoices, json_decode($response->getBody()->getContents(), true));
    }

    #[Test]
    #[TestDox('Deve retornar uma resposta JSON vazia quando não há faturas')]
    public function testProcessWithNoInvoices(): void
    {
        $this->generateInvoices
            ->expects($this->once())
            ->method('generateInvoice')
            ->willReturn(new JsonResponse([]));

        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);

        $request->method('getParsedBody')->willReturn([
            'month' => 3,
            'year' => 2023,
            'type' => 'accrual'
        ]);

        $response = $this->action->process($request, $handler);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], json_decode($response->getBody()->getContents(), true));
    }
}