<?php

declare(strict_types=1);

use App\Action\ListInvoicesAction;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/generate-invoices/{month}/{year}/{type}', ListInvoicesAction::class, 'generate-invoices');
};
