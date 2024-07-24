<?php

namespace SahilBabu\AcmeBasket\Events;

/**
 * Class EventDispatcher
 *
 *  Manages event listeners and dispatches events.
 *
 * @package SahilBabu\AcmeBasket\Events
 * @author      Mudassar Ali <sahil_bwp@yahoo.com>
 * @url   https://github.com/sahilbabu/acme-basket
 */
class EventDispatcher
{
  /**
   * @var array<string, array<int, callable>>
   */
  private array $listeners = [];

  /**
   * Add a listener for a specific event.
   * @param string $eventName
   * @param callable $listener
   */
  public function addListener(string $eventName, callable $listener): void
  {
    if (!isset($this->listeners[$eventName])) {
      $this->listeners[$eventName] = [];
    }
    $this->listeners[$eventName][] = $listener;
  }

  /**
   * Dispatch an event to all registered listeners.
   * @param Event $event
   */
  public function dispatch(Event $event): void
  {
    if (isset($this->listeners[$event->getName()])) {
      foreach ($this->listeners[$event->getName()] as $listener) {
        $listener($event);
      }
    }
  }
}
