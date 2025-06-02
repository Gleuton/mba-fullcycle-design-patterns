<?php

namespace App\Infra\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LogLevel;

class LoggerFactory
{
    public function __invoke(ContainerInterface $container): Logger
    {
        $logger = new Logger('app');

        $settings = $container->get('config')['logger'] ?? [];
        $logFile = $settings['path'] ?? 'data/logs/app.log';
        $logLevel = $settings['level'] ?? LogLevel::DEBUG;

        if (!file_exists($logFile)) {
            if (
                !is_dir(dirname($logFile)) &&
                !mkdir($concurrentDirectory = dirname($logFile), 0755, true) &&
                !is_dir($concurrentDirectory)
            ) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
            touch($logFile);
        }

        $logger->pushHandler(new StreamHandler($logFile, $logLevel));

        return $logger;
    }
}
