<?php

declare(strict_types=1);

use App\Action\ListInvoicesAction;
use Mezzio\Application;

return static function (Application $app): void {
    $app->post('/generate-invoices', ListInvoicesAction::class, 'generate-invoices');
};
