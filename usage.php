<?php
require __DIR__ . '/vendor/autoload.php';

use SahilBabu\AcmeBasket\DependencyInjection\ContainerBuilder;
use SahilBabu\AcmeBasket\Events\{Event,EventDispatcher};


// load from configuration or user hardcode configuration

$productCatalog = [
  'R01' => ['name' => 'Red Widget', 'price' => 32.95],
  'G01' => ['name' => 'Green Widget', 'price' => 24.95],
  'B01' => ['name' => 'Blue Widget', 'price' => 7.95]
];

$deliveryRules = [
  ['threshold' => 50, 'cost' => 4.95],
  ['threshold' => 90, 'cost' => 2.95],
  ['threshold' => PHP_FLOAT_MAX, 'cost' => 0.0]
];

$offers= [
  ['productCode' => 'R01', 'quantity' => 2, 'discount' => 32.95 / 2]
];

// $dispatcher = new EventDispatcher();

$builder = ContainerBuilder::build($productCatalog, $deliveryRules, $offers);

$basket = $builder->get('basket');

$basket->add('B01');
$basket->add('G01');

echo $basket->total(); // Outputs: 37.85
