<?php

namespace StickyBeat\AudioSalad\Services;

use Carbon\Carbon;
use StickyBeat\AudioSalad\Services\Client;
use StickyBeat\AudioSalad\Resources\Label\Labels;
use StickyBeat\AudioSalad\Resources\Track\Tracks;
use StickyBeat\AudioSalad\Resources\Release\Releases;
use StickyBeat\AudioSalad\Resources\Delivery\Deliveries;
use StickyBeat\AudioSalad\Exceptions\AudioSaladException;

class AudioSalad
{
  protected $client;

  /**
   * Constructs a new instance.
   */
  public function __construct()
  {
    $this->client = new Client();
  }

  public function deliveries(): mixed
  {
    return new Deliveries($this->client);
  }

  public function labels(): mixed
  {
    return new Labels($this->client);
  }

  public function releases(): mixed
  {
    return new Releases($this->client);
  }

  public function tracks(): mixed
  {
    return new Tracks($this->client);
  }
}
