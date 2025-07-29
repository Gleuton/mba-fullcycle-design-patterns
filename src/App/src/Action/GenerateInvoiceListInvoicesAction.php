<?php

declare(strict_types=1);

namespace App\Action;

use App\Application\UseCase\GenerateInvoicesInterface;
use App\Infra\Presenter\JsonPresenter;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class GenerateInvoiceListInvoicesAction implements MiddlewareInterface
{
    public function __construct(private GenerateInvoicesInterface $generateInvoices)
    {
    }

    #[Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $input = $request->getParsedBody();

        return $this->generateInvoices->generateInvoice(
            (int) $input['month'],
            (int) $input['year'],
            $input['type'],
            new JsonPresenter()
        );
    }
}
