<?php

namespace SahilBabu\AcmeBasket;

use SahilBabu\AcmeBasket\Models\Product;
use SahilBabu\AcmeBasket\Services\DeliveryService;
use SahilBabu\AcmeBasket\Services\OfferService;
use SahilBabu\AcmeBasket\Interfaces\BasketInterface;
use SahilBabu\AcmeBasket\Services\LoggingService;
use InvalidArgumentException;

/**
 * Class Basket
 *
 * Acme Basket Package
 *
 * @package SahilBabu\AcmeBasket
 * @author      Mudassar Ali <sahil_bwp@yahoo.com>
 * @url   https://github.com/sahilbabu/acme-basket
 */
class Basket implements BasketInterface
{
  /** @var Product[] */
  private array $products = [];
  /** @var array<string, array{name: string, price: float}> */
  private array $productCatalog;
  private DeliveryService $deliveryService;
  private OfferService $offerService;
  private LoggingService $loggingService;

  /**
   * Basket constructor.
   * @param array<string, array{name: string, price: float}> $productCatalog
   * @param DeliveryService $deliveryService
   * @param OfferService $offerService
   * @param LoggingService $loggingService
   */
  public function __construct(
    array $productCatalog,
    DeliveryService $deliveryService,
    OfferService $offerService,
    LoggingService $loggingService
  ) {
    $this->productCatalog = $productCatalog;
    $this->deliveryService = $deliveryService;
    $this->offerService = $offerService;
    $this->loggingService = $loggingService;
  }

  /**
   * Add a product to the basket.
   * @param string $productCode
   */
  public function add(string $productCode): void
  {
    if (!isset($this->productCatalog[$productCode])) {
      $this->loggingService->log("Invalid product code: $productCode");
      throw new InvalidArgumentException("Invalid product code: $productCode");
    }
    $this->products[] = new Product(
      $productCode,
      $this->productCatalog[$productCode]['name'],
      (int) ($this->productCatalog[$productCode]['price'] * 100)
    );
    $this->loggingService->log("Added product: $productCode");
  }

  /**
   * Remove a product from the basket.
   * @param string $productCode
   */
  public function remove(string $productCode): void
  {
    foreach ($this->products as $index => $product) {
      if ($product->code === $productCode) {
        unset($this->products[$index]);
        $this->products = array_values($this->products);  // Reindex the array
        $this->loggingService->log("Removed product: $productCode");
        return;
      }
    }
    $this->loggingService->log("Product code not found in basket: $productCode");
    throw new InvalidArgumentException("Product code not found in basket: $productCode");
  }

  /**
   * Clear all products from the basket.
   */
  public function clear(): void
  {
    $this->products = [];
    $this->loggingService->log("Cleared all products from the basket");
  }

  /**
   * Get the list of products in the basket.
   * @return Product[]
   */
  public function getProducts(): array
  {
    return $this->products;
  }

  /**
   * Calculate the total cost in cents.
   * @return int
   */
  public function totalInCents(): int
  {
    $subtotal = array_reduce($this->products, function ($sum, $product) {
      return $sum + $product->price;
    }, 0);

    $discount = $this->offerService->applyOffers($this->products);
    $totalAfterDiscount = $subtotal - $discount;

    $deliveryCost = $this->deliveryService->getDeliveryCost($totalAfterDiscount);

    $total = $totalAfterDiscount + $deliveryCost;
    $this->loggingService->log("Total cost calculated: " . $total / 100);

    return (int) round($total);
  }

  /**
   * Get the total cost in dollars.
   * @return float
   */
  public function total(): float
  {
    return $this->totalInCents() / 100;
  }

  /**
   * Get the total cost as a formatted string in dollars.
   * @return string
   */
  public function getTotalInDollars(): string
  {
    return number_format($this->total(), 2);
  }
}
