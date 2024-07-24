# Acme Basket

This package implements a shopping basket for Acme Widget Co with delivery rules and special offers. It supports dependency injection, fixed-point arithmetic for monetary values, and offers logging for operations.

## Features

- Add, remove, and clear products in the basket.
- Calculate total costs including special offers and delivery fees.
- Configurable product catalog, delivery rules, and offers.
- Events Dispatcher support (Product add /remove , offer apply  etc).
- Logging of operations.
- Unit tests 
- Static analysis.

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer

### Step 1: Install the Package

Install via package or Using Git  Open your shell command
####  Install  using GIT:

```bash
mkdir  your_dir_name
git  clone https://github.com/sahilbabu/acme-basket.git .
cd your_dir_name
```
####  OR Install  using Composer (as package):

```bash
composer require sahilbabu/acme-basket
```

### Step 2:  Install Dependencies 

####  OR Install  using Composer (as package):
```bash
cd your_dir_name
composer install 
```

### Step 3: Build and Run the Docker Containers
you can use a **'Dockerfile'** and a docker-compose.yml file to set up the necessary environment. Below are the steps to use Docker

#### Build the Docker containers
The Dockerfile will define the PHP environment and install necessary dependencies..
```bash
docker-compose build
          OR
docker compose up
```
#### Start the Docker containers
The docker-compose.yml file will define the services required for your application.
```bash
docker-compose up -d
          OR
docker compose  up -d
```

####  List Running Containers

```bash
docker ps
```

####  Access the Shell of the Container (for PHP)

```bash
docker exec -it php_app /bin/bash
```

####  For the Nginx container

```bash
docker exec -it nginx /bin/bash
```
####  Run Commands Inside the Container

```bash
# Inside the PHP container

# List files
ls -la

# Run Composer commands
composer install

# Run PHPUnit tests
./vendor/bin/phpunit

# Run PHPStan
./vendor/bin/phpstan analyse

```

####  Run Commands Inside the Container

```bash
exit
```

## Testing

### Run PHPUnit tests

```bash
composer test
      OR
 vendor/bin/phpunit
```

### Run PHPStan for static analysis

```bash
composer phpstan
            OR 
vendor/bin/phpstan analyse -l 9 src test
```

## Example Commands
Here are some common commands you might run in your PHP container:

### Running Composer Install

```bash
docker exec -it php_app /bin/bash -c "composer install"
```

### Running PHPUnit Tests

```bash
docker exec -it php_app /bin/bash -c "./vendor/bin/phpunit"
```

### Running PHPStan Analysis

```bash
docker exec -it php_app /bin/bash -c "./vendor/bin/phpstan analyse"
```


## GitHub Actions Workflow File
In the root of your repository,  a directory named .github/workflows.

Navigate to your repository on GitHub: like 

- Go to https://github.com/sahilbabu/acme-basket.
- Update CI/CD   .github/workflows/ci.yml 

### Commit and Push the Workflow
```bash
git add .github/workflows/ci.yml
git commit -m "Add GitHub Actions CI/CD pipeline"
git push origin main
```

## Usage Example

For usage and test examples please see the files in source folder below
- tests/BasketTests.php
- usage.php

```bash
require __DIR__ . '/vendor/autoload.php';

use SahilBabu\AcmeBasket\DependencyInjection\ContainerBuilder;


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

$builder = ContainerBuilder::build($productCatalog, $deliveryRules, $offers);

$basket = $builder->get('basket');

$basket->add('B01');
$basket->add('G01');

echo $basket->total(); // Outputs: 37.85


```
- Special offers are configurable through the DI container.
- The delivery cost and product prices are configurable through the DI container.



## Assumptions

- Special offers are configurable through the DI container.
- The delivery cost and product prices are configurable through the DI container.
