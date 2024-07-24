<?php

namespace SahilBabu\AcmeBasket\Services;

use SahilBabu\AcmeBasket\Models\Product;

/**
 * Class OfferService
 * @package SahilBabu\AcmeBasket\Services
 * @author      Mudassar Ali <sahil_bwp@yahoo.com>
 * @url   https://github.com/sahilbabu/acme-basket
 */
class OfferService
{
  /** @var array<int, array{productCode: string, quantity: int, discount: float}> */
  private array $offers;

  /**
   * OfferService constructor.
   * @param array<int, array{productCode: string, quantity: int, discount: float}> $offers
   */
  public function __construct(array $offers)
  {
    $this->offers = $offers;
  }

  /**
   * Apply offers to the products in the basket and calculate the total discount.
   * @param Product[] $products
   * @return int
   */
  public function applyOffers(array $products): int
  {
    $totalDiscount = 0;

    foreach ($this->offers as $offer) {
      $productCounts = array_count_values(array_map(fn($product) => $product->code, $products));
      if (isset($productCounts[$offer['productCode']]) && $productCounts[$offer['productCode']] >= $offer['quantity']) {
        $discountMultiplier = floor($productCounts[$offer['productCode']] / $offer['quantity']);
        $totalDiscount += (int) round($discountMultiplier * $offer['discount'] * 100);
      }
    }

    return $totalDiscount;
  }
}
