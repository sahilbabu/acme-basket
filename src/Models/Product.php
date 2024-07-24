<?php

namespace SahilBabu\AcmeBasket\Models;
/**
 * Class Product
 * @package SahilBabu\AcmeBasket\Models
 * @author      Mudassar Ali <sahil_bwp@yahoo.com>
 * @url   https://github.com/sahilbabu/acme-basket
 */
class Product
{

  /**
   * @var string
   */
  public string $code;

  /**
   * @var string
   */
  public string $name;

  /**
   *  Price in cents
   * @var Integer
   */
  public int $price;

  /**
   * Product constructor.
   * @param string $code
   * @param string $name
   * @param int $price
   */
  public function __construct(string $code, string $name, int $price)
  {
    $this->code = $code;
    $this->name = $name;
    $this->price = $price;
  }

  /**
   * Get the price in dollars.
   * @return float
   */
  public function getPriceInDollars(): float
  {
    return $this->price / 100;
  }
}
