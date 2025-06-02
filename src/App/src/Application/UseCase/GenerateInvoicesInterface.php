<?php

namespace App\Application\UseCase;

use App\Application\Presenter\PresenterInterface;

interface GenerateInvoicesInterface
{
    public function generateInvoice(int $month, int $year, string $type, PresenterInterface $presenter): mixed;
}