<?php

namespace App\Application\Decorator;

use App\Application\Presenter\PresenterInterface;
use App\Application\UseCase\GenerateInvoicesInterface;
use Psr\Log\LoggerInterface;

class LoggerDecorator implements GenerateInvoicesInterface
{
    public function __construct(
        private readonly GenerateInvoicesInterface $useCase,
        private readonly LoggerInterface $log
    ) {
    }

    public function generateInvoice(int $month, int $year, string $type, PresenterInterface $presenter): mixed
    {
        $this->log->info(
            'Executing use case: GenerateInvoices',
            [
                'month' => $month,
                'year' => $year,
                'type' => $type,
            ]
        );
        return $this->useCase->generateInvoice($month, $year, $type, $presenter);
    }
}