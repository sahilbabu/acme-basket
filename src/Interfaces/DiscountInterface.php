<?php

namespace SahilBabu\AcmeBasket\Interfaces;

use SahilBabu\AcmeBasket\Models\Product;

/**
 * Interface DiscountInterface
 *
 * Acme Basket Package
 *
 * @package SahilBabu\AcmeBasket
 * @author      Mudassar Ali <sahil_bwp@yahoo.com>
 * @url   https://github.com/sahilbabu/acme-basket
 */
interface DiscountInterface
{

  /**
   * Add a product to the basket.
   *
   * @param Product[] $products
   * @return int
   */
  public function  applyOffers(array $products): int;

}
