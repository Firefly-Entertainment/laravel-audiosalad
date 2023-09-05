<?php

namespace StickyBeat\AudioSalad\Services;

use Carbon\Carbon;
use StickyBeat\AudioSalad\Services\Client;
use StickyBeat\AudioSalad\Resources\Image\Images;
use StickyBeat\AudioSalad\Resources\Label\Labels;
use StickyBeat\AudioSalad\Resources\Track\Tracks;
use StickyBeat\AudioSalad\Resources\Release\Releases;
use StickyBeat\AudioSalad\Resources\Delivery\Deliveries;
use StickyBeat\AudioSalad\Exceptions\AudioSaladException;
use StickyBeat\AudioSalad\Resources\Ingestion\Ingestions;

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

  public function deliveries(): Deliveries
  {
    return new Deliveries($this->client);
  }

  public function labels(): Labels
  {
    return new Labels($this->client);
  }

  public function releases(): Releases
  {
    return new Releases($this->client);
  }

  public function tracks(): Tracks
  {
    return new Tracks($this->client);
  }

  public function images(): Images
  {
    return new Images($this->client);
  }

  public function ingestions(): Ingestions
  {
    return new Ingestions($this->client);
  }
}
