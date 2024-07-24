<?php

namespace SahilBabu\AcmeBasket\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Psr\Log\NullLogger;
use SahilBabu\AcmeBasket\Services\DeliveryService;
use SahilBabu\AcmeBasket\Services\OfferService;
use SahilBabu\AcmeBasket\Basket;
use SahilBabu\AcmeBasket\Services\LoggingService;

/**
 * Class ContainerBuilder
 * @package SahilBabu\AcmeBasket\DependencyInjection
 * @author      Mudassar Ali <sahil_bwp@yahoo.com>
 * @url   https://github.com/sahilbabu/acme-basket
 */
class ContainerBuilder
{
  /**
   * Build the DI container.
   * @param array<string, array{name: string, price: float}> $productCatalog
   * @param array<int, array{threshold: float, cost: float}> $deliveryRules
   * @param array<int, array{productCode: string, quantity: int, discount: float}> $offers
   * @return SymfonyContainerBuilder
   */
  public static function build(array $productCatalog, array $deliveryRules, array $offers): SymfonyContainerBuilder
  {
    $containerBuilder = new SymfonyContainerBuilder();

    $containerBuilder->register('delivery_service', DeliveryService::class)
      ->addArgument($deliveryRules);

    $containerBuilder->register('offer_service', OfferService::class)
      ->addArgument($offers);

    $containerBuilder->register('logger', NullLogger::class);

    $containerBuilder->register('logging_service', LoggingService::class)
      ->addArgument(new Reference('logger'));

    $containerBuilder->register('basket', Basket::class)
      ->addArgument($productCatalog)
      ->addArgument(new Reference('delivery_service'))
      ->addArgument(new Reference('offer_service'))
      ->addArgument(new Reference('logging_service'));

    return $containerBuilder;
  }
}
