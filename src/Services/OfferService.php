<?php

namespace SahilBabu\AcmeBasket\Services;

use SahilBabu\AcmeBasket\Models\Product;
use SahilBabu\AcmeBasket\Events\{Event, EventDispatcher};

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

  private EventDispatcher $dispatcher;

  /**
   * OfferService constructor.
   * @param array<int, array{productCode: string, quantity: int, discount: float}> $offers
   */
  public function __construct(array $offers, EventDispatcher $dispatcher)
  {
    $this->offers = $offers;
    $this->dispatcher = $dispatcher;
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
        /**
         * Even if the discount
         */
        $this->dispatcher->dispatch(new Event('offer_applied', ['offer' => $productCounts[$offer['productCode']], 'discount' => $totalDiscount]));
      }
    }

    return $totalDiscount;
  }
}
