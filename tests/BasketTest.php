<?php

namespace SahilBabu\AcmeBasket\Tests;

use PHPUnit\Framework\TestCase;
use SahilBabu\AcmeBasket\{Basket, Product};
use SahilBabu\AcmeBasket\Services\{DeliveryService, LoggingService, OfferService};
use Psr\Log\NullLogger;
use SahilBabu\AcmeBasket\Events\EventDispatcher;

/**
 * Class BasketTest
 *
 * Unit tests for Basket
 * @package SahilBabu\AcmeBasket\Tests
 * @author      Mudassar Ali <sahil_bwp@yahoo.com>
 * @url   https://github.com/sahilbabu/acme-basket
 */
class BasketTest extends TestCase
{
  /** @var array<string, array{name: string, price: float}> */
  private array $productCatalog;
  /** @var array<int, array{threshold: float, cost: float}> */
  private array $deliveryRules;
  /** @var array<int, array{productCode: string, quantity: int, discount: float}> */
  private array $offers;

  protected function setUp(): void
  {
    $this->productCatalog = [
      'R01' => ['name' => 'Red Widget', 'price' => 32.95],
      'G01' => ['name' => 'Green Widget', 'price' => 24.95],
      'B01' => ['name' => 'Blue Widget', 'price' => 7.95]
    ];

    $this->deliveryRules = [
      ['threshold' => 50, 'cost' => 4.95],
      ['threshold' => 90, 'cost' => 2.95],
      ['threshold' => PHP_FLOAT_MAX, 'cost' => 0.0]
    ];

    $this->offers = [
      ['productCode' => 'R01', 'quantity' => 2, 'discount' => 32.95 / 2]
    ];
  }

  /**
   * Test the basket totals.
   * @return void
   */
  public function testBasketTotals(): void
  {
    $dispatcher = new EventDispatcher();
    $deliveryService = new DeliveryService($this->deliveryRules);
    $offerService = new OfferService($this->offers, $dispatcher);
    $loggingService = new LoggingService(new NullLogger());

    $basket = new Basket($this->productCatalog, $deliveryService, $offerService, $loggingService, $dispatcher);

    $basket->add('B01');
    $basket->add('G01');
    // $this->assertEquals(37.85, $basket->total(), '', 0.01);
    $this->assertEquals(37.85, $basket->total());
    $this->assertEquals('37.85', $basket->getTotalInDollars());

    $basket = new Basket($this->productCatalog, $deliveryService, $offerService, $loggingService, $dispatcher);
    $basket->add('R01');
    $basket->add('R01');
    $this->assertEquals(54.37, $basket->total());
    $this->assertEquals('54.37', $basket->getTotalInDollars());

    $basket = new Basket($this->productCatalog, $deliveryService, $offerService, $loggingService, $dispatcher);
    $basket->add('R01');
    $basket->add('G01');
    $this->assertEquals(60.85, $basket->total());
    $this->assertEquals('60.85', $basket->getTotalInDollars());

    $basket = new Basket($this->productCatalog, $deliveryService, $offerService, $loggingService, $dispatcher);
    $basket->add('B01');
    $basket->add('B01');
    $basket->add('R01');
    $basket->add('R01');
    $basket->add('R01');
    $this->assertEquals(98.27, $basket->total());
    $this->assertEquals('98.27', $basket->getTotalInDollars());
  }

  /**
   * Test clearing the basket.
   * @return void
   */
  public function testClearBasket(): void
  {
    $dispatcher = new EventDispatcher();
    $deliveryService = new DeliveryService($this->deliveryRules);
    $offerService = new OfferService($this->offers, $dispatcher);
    $loggingService = new LoggingService(new NullLogger());


    $basket = new Basket($this->productCatalog, $deliveryService, $offerService, $loggingService, $dispatcher);

    $basket->add('B01');
    $basket->add('G01');
    $this->assertCount(2, $basket->getProducts());

    $basket->clear();
    $this->assertCount(0, $basket->getProducts());
  }
}
