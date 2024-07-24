<?php

namespace SahilBabu\AcmeBasket\Interfaces;

use SahilBabu\AcmeBasket\Models\Product;

/**
 * Interface BasketInterface
 *
 * Acme Basket Package
 *
 * @package SahilBabu\AcmeBasket
 * @author      Mudassar Ali <sahil_bwp@yahoo.com>
 * @url   https://github.com/sahilbabu/acme-basket
 */
interface BasketInterface
{
  /**
   * Add a product to the basket.
   * @param string $productCode
   */
  public function add(string $productCode): void;

  /**
   * Remove a product from the basket.
   * @param string $productCode
   */
  public function remove(string $productCode): void;

  /**
   * Clear all products from the basket.
   */
  public function clear(): void;

  /**
   * Get the list of products in the basket.
   * @return Product[]
   */
  public function getProducts(): array;

  /**
   * Calculate the total cost in cents.
   * @return int
   */
  public function totalInCents(): int;

  /**
   * Get the total cost in dollars.
   * @return float
   */
  public function total(): float;

  /**
   * Get the total cost as a formatted string in dollars.
   * @return string
   */
  public function getTotalInDollars(): string;
}
