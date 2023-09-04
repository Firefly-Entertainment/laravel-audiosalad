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

  /**
   *  Detail of release object. Only one search field will be used with precedence taking place in the following order: release_id, catalog, upc, custom_id. At least one search field is required.
   *
   * @param $releaseId integer	Valid Release ID
   * @param $catalog string	Valid Catalog Number
   * @param $upc string	Valid UPC
   * @param $customId string	Valid Custom ID
   *
   * @return Release
   */
  public function release(
    int $releaseId = null,
    string $catalog = null,
    string $upc = null,
    string $customId = null
  ): mixed {
    // Check which arguments are set and set the data array accordingly
    $data = [];
    if ($releaseId != null) {
      $data["release_id"] = $releaseId;
    }
    if ($catalog != null) {
      $data["catalog"] = $catalog;
    }
    if ($upc != null) {
      $data["upc"] = $upc;
    }
    if ($customId != null) {
      $data["custom_id"] = $customId;
    }

    if (count($data) === 0) {
      throw new AudioSaladException("Invalid arguments");
    }

    return $this->client->get("release", $data)->json();
  }

  /**
   * Retrieve a list of release IDs filtered by modified date
   *
   * @param $modifiedStart string	iso8601 formatted date eg: 2022-08-15T13:29:02+00:00 or GMT 2022-08-15T13:29:02Z'
   * @param $modifiedEnd string	iso8601 formatted date eg: 2022-08-15T13:29:02+00:00 or GMT 2022-08-15T13:29:02Z'
   *
   * @return array[integer]
   */
  public function releaseIds(string $modifiedStart, string $modifiedEnd): array
  {
    // Make sure date has correct format
    $parsedModifiedStart = Carbon::parse($modifiedStart);
    $parsedModifiedEnd = Carbon::parse($modifiedEnd);

    $data = [
      "modified_start" => $parsedModifiedStart->format("Y-m-d H:i:s"),
      "modified_end" => $parsedModifiedEnd->format("Y-m-d H:i:s"),
    ];

    return $this->client->get("release-ids", $data)->json();
  }
}
