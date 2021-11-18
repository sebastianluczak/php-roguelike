<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class LoggerService
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function log(string $message)
    {
        $this->logger->info($message);
    }
}