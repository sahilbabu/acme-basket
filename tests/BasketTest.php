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

  private $config;
  private $deliveryService;
  private $offerService;
  private $loggingService;
  private $basket;

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

    $dispatcher = new EventDispatcher();
    $this->deliveryService = new DeliveryService($this->deliveryRules);
    $this->offerService = new OfferService($this->offers, $dispatcher);
    $this->loggingService = new LoggingService(new NullLogger());
    $this->basket = new Basket(
      $this->productCatalog,
      $this->deliveryService,
      $this->offerService,
      $this->loggingService,
      $dispatcher
    );
  }

  /**
   * Test add product to the basket.
   * @return void
   */
  public function testAddProduct()
  {
    $this->basket->add('R01');
    $items = $this->basket->getProducts();
    $this->assertCount(1, $items);
    $this->assertSame('R01', $items[0]->code);
  }

  /**
   * Test remove product from the basket.
   * @return void
   */
  public function testRemoveProduct()
  {
    $this->basket->add('R01');
    $this->basket->remove('R01');
    $this->assertCount(0, $this->basket->getProducts());
    $this->basket->clear();
  }


  /**
   * Test the basket totals with offers
   * @return void
   */
  public function testTotalWithOffers(): void
  {
    $this->basket->add('B01');
    $this->basket->add('G01');
    // $this->assertEquals(37.85, $this->basket->total(), '', 0.01);
    $this->assertEquals(37.85, $this->basket->total());
    $this->assertEquals('37.85', $this->basket->getTotalInDollars());
    $this->basket->clear();


    $this->basket->add('R01');
    $this->basket->add('R01');
    $this->assertEquals(54.37, $this->basket->total());
    $this->assertEquals('54.37', $this->basket->getTotalInDollars());
    $this->basket->clear();

    $this->basket->add('R01');
    $this->basket->add('G01');
    $this->assertEquals(60.85, $this->basket->total());
    $this->assertEquals('60.85', $this->basket->getTotalInDollars());
    $this->basket->clear();
  }

  /**
   * Test the with offer and delivery charges.
   * @return void
   */
  public function testTotalWithOffersAndDeliveryCharges()
  {
    $this->basket->add('B01');
    $this->basket->add('B01');
    $this->basket->add('R01');
    $this->basket->add('R01');
    $this->basket->add('R01');
    $this->assertEquals(98.27, $this->basket->total());
    $this->assertEquals('98.27', $this->basket->getTotalInDollars());
    $this->basket->clear();
  }

  /**
   * Test clearing the basket.
   * @return void
   */
  public function testClearBasket(): void
  {
    $this->basket->add('B01');
    $this->basket->add('G01');
    $this->assertCount(2, $this->basket->getProducts());

    $this->basket->clear();
    $this->assertCount(0, $this->basket->getProducts());
  }
}
