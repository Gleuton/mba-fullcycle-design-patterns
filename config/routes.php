<?php

declare(strict_types=1);

use App\Action\GenerateInvoiceListInvoicesAction;
use Mezzio\Application;

return static function (Application $app): void {
    $app->post('/generate-invoices', GenerateInvoiceListInvoicesAction::class, 'generate-invoices');
};
