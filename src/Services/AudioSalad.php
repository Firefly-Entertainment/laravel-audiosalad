<?php

namespace StickyBeat\AudioSalad\Services;

use Carbon\Carbon;
use StickyBeat\AudioSalad\Exceptions\AudioSaladException;
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
        "run_date" => $parsedRunDate->format("Y-m-d H:i:s"),
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
  public function deliveryStatus(
    int $releaseIds = null,
    int $targetIds = null
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
  public function deliveryTargets(): array
  {
    $response = $this->client->get("delivery-targets");

    return $response->json();
  }

  /**
   *
   * Detail of label object. Only one search field will be used with precedence taking place in the following order: label_id, label_name. Leave both fields blank to receive a full list.
   *
   *@param $labelId integer	Valid Label ID
   * @param $labelName string	Valid Label Name
   *
   * @return Label | array[Label]
   */
  public function label(int $labelId = null, string $labelName = null): mixed
  {
    if ($labelId != null && $labelName != null) {
      $data = [
        "label_id" => $labelId,
      ];
    } elseif ($labelId != null) {
      $data = [
        "label_id" => $labelId,
      ];
    } elseif ($labelName != null) {
      $data = [
        "label_name" => $labelName,
      ];
    } else {
      $data = [];
    }

    return $this->client->get("label", $data)->json();
  }
}
