<?php

namespace SahilBabu\AcmeBasket\Services;

use Psr\Log\LoggerInterface;

/**
 * Class LoggingService
 * @package SahilBabu\AcmeBasket\Services
 * @author      Mudassar Ali <sahil_bwp@yahoo.com>
 * @url   https://github.com/sahilbabu/acme-basket
 */
class LoggingService
{
  
  /**
   * @var LoggerInterface Log
   */
  private LoggerInterface $logger;

  /**
   * LoggingService constructor.
   * @param LoggerInterface $logger
   */
  public function __construct(LoggerInterface $logger)
  {
    $this->logger = $logger;
  }

  /**
   * Log a message.
   * @param string $message
   */
  public function log(string $message): void
  {
    $this->logger->info($message);
  }
}
