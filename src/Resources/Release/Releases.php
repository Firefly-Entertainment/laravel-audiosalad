<?php

namespace StickyBeat\AudioSalad\Resources\Release;

use Illuminate\Support\Carbon;
use StickyBeat\AudioSalad\Services\Client;
use StickyBeat\AudioSalad\Interfaces\ResourceInterface;
use StickyBeat\AudioSalad\Exceptions\AudioSaladException;

class Releases implements ResourceInterface
{
  protected $client;

  public function __construct(Client $client)
  {
    $this->client = $client;
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
  public function get(
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
  public function ids(string $modifiedStart, string $modifiedEnd): array
  {
    // Make sure date has correct format
    $parsedModifiedStart = Carbon::parse($modifiedStart);
    $parsedModifiedEnd = Carbon::parse($modifiedEnd);

    $data = [
      "modified_start" => $parsedModifiedStart->toIso8601ZuluString(),
      "modified_end" => $parsedModifiedEnd->toIso8601ZuluString(),
    ];

    return $this->client->get("release-ids", $data)->json();
  }
}
