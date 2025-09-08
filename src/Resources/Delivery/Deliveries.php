<?php

namespace Firefly\AudioSalad\Resources\Delivery;

use Illuminate\Support\Carbon;
use Firefly\AudioSalad\Services\Client;
use Firefly\AudioSalad\Interfaces\ResourceInterface;
use Firefly\AudioSalad\Exceptions\AudioSaladException;

class Deliveries implements ResourceInterface
{
  protected $client;

  public function __construct(Client $client)
  {
    $this->client = $client;
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
  public function schedule(
    array $releaseIds,
    array $targetIds,
    string $runDate,
    string $action
  ) {
    // Check that all arrguments are valid
    if (
      !is_array($releaseIds) ||
      !is_array($targetIds) ||
      !is_string($runDate) ||
      !is_string($action)
    ) {
      throw new AudioSaladException("Invalid arguments");
    }

    // Check that action is valid
    if (
      $action != "clear" &&
      $action != "add" &&
      $action != "full-update" &&
      $action != "meta-update" &&
      $action != "delete"
    ) {
      throw new AudioSaladException("Invalid action");
    }

    $parsedRunDate = Carbon::parse($runDate);

    return $this->client
      ->post("delivery", [
        "release_ids" => $releaseIds,
        "target_ids" => $targetIds,
        "run_date" => $parsedRunDate->toIso8601ZuluString(),
        "action" => $action,
      ])
      ->successful();
  }

  /**
   * This will be a list of current deliveries with status, filtered by release_id, target_id, or both.
   *
   * @param $releaseIds array[integer]	Valid Release IDs
   * @param $targetIds array[integer]	Valid Target IDs
   *
   * @return array[Delivery]
   */
  public function status(
    array $releaseIds = null,
    array $targetIds = null
  ): array {
    if ($releaseIds != null && $targetIds != null) {
      $data = [
        "release_ids" => $releaseIds,
        "target_ids" => $targetIds,
      ];
    } elseif ($releaseIds != null) {
      $data = [
        "release_ids" => $releaseIds,
      ];
    } elseif ($targetIds != null) {
      $data = [
        "target_ids" => $targetIds,
      ];
    } else {
      throw new AudioSaladException("Invalid arguments");
    }

    $response = $this->client->post("delivery-status", $data);

    return $response->json();
  }

  /**
   * This will retreive list of all of your current configured delivery targets
   *
   *
   * @return array[DeliveryTarget]
   */
  public function targets(): array
  {
    $response = $this->client->get("delivery-targets");

    return $response->json();
  }
}
