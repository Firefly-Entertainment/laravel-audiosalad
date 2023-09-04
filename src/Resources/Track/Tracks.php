<?php

namespace StickyBeat\AudioSalad\Resources\Track;

use Illuminate\Support\Carbon;
use StickyBeat\AudioSalad\Services\Client;
use StickyBeat\AudioSalad\Interfaces\ResourceInterface;
use StickyBeat\AudioSalad\Exceptions\AudioSaladException;

class Tracks implements ResourceInterface
{
  protected $client;

  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  /**
   * Detail of track object. Only one search field will be used with precedence taking place in the following order: track_id, isrc, custom_id. At least one search field is required.
   *
   * @param $trackId integer	Valid Track ID
   * @param $isrc string	Valid ISRC
   * @param $customId string	Valid Custom ID
   *
   * @return Track
   */
  public function get(
    int $trackId = null,
    string $isrc = null,
    string $customId = null
  ): mixed {
    // Check which arguments are set and set the data array accordingly
    $data = [];
    if ($trackId != null) {
      $data["track_id"] = $trackId;
    }
    if ($isrc != null) {
      $data["isrc"] = $isrc;
    }
    if ($customId != null) {
      $data["custom_id"] = $customId;
    }

    if (count($data) === 0) {
      throw new AudioSaladException("Invalid arguments");
    }

    return $this->client->get("track", $data)->json();
  }

  /**
   * Retrieve a list of track IDs filtered by modified date
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
      "modified_start" => $parsedModifiedStart->format("Y-m-d H:i:s"),
      "modified_end" => $parsedModifiedEnd->format("Y-m-d H:i:s"),
    ];

    return $this->client->get("track-ids", $data)->json();
  }

  /**
   * Retreive an audio file for given track and type
   *
   * @param $trackId integer	Valid Track ID
   * @param $type string 'clip', 'mp3-320', or 'mp3-96'. Determines what type of file is served
   *
   * @return File
   */

  public function audio(int $trackId, string $type): mixed
  {
    // Check that type is valid
    if ($type != "clip" && $type != "mp3-320" && $type != "mp3-96") {
      throw new AudioSaladException("Invalid type");
    }

    return $this->client
      ->get("track-audio", [
        "track_id" => $trackId,
        "type" => $type,
      ])
      ->json();
  }
}
