<?php

namespace AppTest\Functional;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class ContractGenerateInvoicesFunctionalTest extends TestCase
{
    private Client $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new Client([
            'base_uri' => 'http://mba-patters-nginx',
            'http_errors' => false,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    #[Test]
    #[TestDox('Deve gerar faturas pelo regime de caixa para um mês específico')]
    public function testGenerateInvoicesCashForSpecificMonth(): void
    {
        $response = $this->httpClient->post('/generate-invoices', [
            'json' => [
                'month' => 2,
                'year' => 2023,
                'type' => 'cash',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $invoices = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(1, $invoices);
        $this->assertEquals(6000, $invoices[0]['amount']);
        $this->assertEquals('2023-02-01', $invoices[0]['date']);
    }

    #[Test]
    #[TestDox('Deve gerar faturas pelo regime de competência para um mês específico')]
    public function testGenerateInvoicesAccrualForSpecificMonth(): void
    {
        $response = $this->httpClient->post('/generate-invoices',[
            'json' => [
                'month' => 1,
                'year' => 2023,
                'type' => 'accrual',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $invoices = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(1, $invoices);
    }

    #[Test]
    #[TestDox('Deve retornar vazio para mês inválido')]
    public function testGenerateInvoicesInvalidMonth(): void
    {
        $response = $this->httpClient->post('/generate-invoices',[
            'json' => [
                'month' => 13,
                'year' => 2023,
                'type' => 'accrual',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $invoices = json_decode($response->getBody()->getContents(), true);

        $this->assertEmpty($invoices);
    }

    #[Test]
    #[TestDox('Deve retornar vazio para ano inválido')]
    public function testGenerateInvoicesInvalidYear(): void
    {
        $response = $this->httpClient->post('/generate-invoices',[
            'json' => [
                'month' => 1,
                'year' => 0,
                'type' => 'accrual',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $invoices = json_decode($response->getBody()->getContents(), true);

        $this->assertEmpty($invoices);
    }
}
