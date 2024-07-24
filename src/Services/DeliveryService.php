<?php

namespace SahilBabu\AcmeBasket\Services;

/**
 * Class DeliveryService
 * @package SahilBabu\AcmeBasket\Services
 * @author      Mudassar Ali <sahil_bwp@yahoo.com>
 * @url   https://github.com/sahilbabu/acme-basket
 */
class DeliveryService
{
  /** @var array<int, array{threshold: float, cost: float}> */
  private array $deliveryRules;

  /**
   * DeliveryService constructor.
   * @param array<int, array{threshold: float, cost: float}> $deliveryRules
   */
  public function __construct(array $deliveryRules)
  {
    $this->deliveryRules = $deliveryRules;
  }

  /**
   * Get the delivery cost based on the total amount.
   * @param int $totalInCents
   * @return int
   */
  public function getDeliveryCost(int $totalInCents): int
  {
    foreach ($this->deliveryRules as $rule) {
      if ($totalInCents < $rule['threshold'] * 100) {
        return (int) ($rule['cost'] * 100);
      }
    }
    return 0;
  }
}
