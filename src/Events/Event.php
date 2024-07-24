<?php

namespace SahilBabu\AcmeBasket\Events;

/**
 * Class Event
 *
 *  Represents an event in the system.
 *
 * @package SahilBabu\AcmeBasket\Events
 * @author      Mudassar Ali <sahil_bwp@yahoo.com>
 * @url   https://github.com/sahilbabu/acme-basket
 */
class Event
{
  /**
   * @var string
   */
  private string $name;

  /**
   * @var array<string, mixed>
   */
  private array $data;

  /**
   * Event constructor.
   * @param string $name
   * @param array<string, mixed> $data
   */
  public function __construct(string $name, array $data = [])
  {
    $this->name = $name;
    $this->data = $data;
  }

  /**
   * Get the event name.
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Get the event data.
   * @return array<string, mixed>
   */
  public function getData(): array
  {
    return $this->data;
  }
}
