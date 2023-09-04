<?php

namespace StickyBeat\AudioSalad\Services;

use StickyBeat\AudioSalad\Services\Client;

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

  /**
   *
   * @param $releaseIds array[integer]	Valid Release IDs. Releases must be in 'ready', 'published', or 'archived' state
   * @param $targetIds	array[integer]	Valid Target IDs. Targets must not have user_restrict set
   * @param $runDate	string	iso8601 formatted date eg: 2022-08-15T13:29:02+00:00 or GMT 2022-08-15T13:29:02Z'
   * @param $action string	Must be a valid action: 'clear', 'add', 'full-update', 'meta-update', or 'delete'
   *
   * @return bool true if successful, false otherwise
   */
  public function scheduleDelivery(
    array $releaseIds,
    array $targetIds,
    string $runDate,
    string $action
  ) {
  }

  /**
   * This will retreive list of all of your current configured delivery targets
   * @return array[DeliveryTarget]
   */
  public function deliveryTargets()
  {
    $response = $this->client->get("delivery-targets");
  }
}
