<?php

declare(strict_types=1);

namespace App\Action;

use App\GenerateInvoices;
use Laminas\Diactoros\Response\JsonResponse;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class ListInvoicesAction implements MiddlewareInterface
{
    public function __construct(private GenerateInvoices $generateInvoices)
    {
    }

    #[Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $input = $request->getParsedBody();
        $mouth = 3;
        $year = 2023;
        $type = 'accrual';
        $contracts = $this->generateInvoices->generateInvoice($mouth, $year, $type);

        return new JsonResponse($contracts);
    }
}
