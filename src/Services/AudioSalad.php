<?php

namespace Firefly\AudioSalad\Services;

use Carbon\Carbon;
use Firefly\AudioSalad\Services\Client;
use Firefly\AudioSalad\Resources\Image\Images;
use Firefly\AudioSalad\Resources\Label\Labels;
use Firefly\AudioSalad\Resources\Track\Tracks;
use Firefly\AudioSalad\Resources\Release\Releases;
use Firefly\AudioSalad\Resources\Delivery\Deliveries;
use Firefly\AudioSalad\Exceptions\AudioSaladException;
use Firefly\AudioSalad\Resources\Ingestion\Ingestions;

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
