<?php

namespace StickyBeat\AudioSalad\Resources\Ingestion;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use StickyBeat\AudioSalad\Services\Client;
use StickyBeat\AudioSalad\Interfaces\ResourceInterface;
use StickyBeat\AudioSalad\Exceptions\AudioSaladException;

class Ingestions implements ResourceInterface
{
  protected $client;

  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  /**
   * Scans the given S3 bucket for new files.
   *
   * @param $bucket string	Valid S3 bucket name
   * @param $accessId string	Valid S3 access ID
   * @param $secretKey string	Valid S3 secret key
   * @param $path string	(optional) Valid S3 path to scan
   *
   * @return array  [xml_ready: integer, flac_ready: integer, wav_ready: integer, mp3_ready: integer, video_ready: integer]
   *
   */
  public function scan(
    string $bucket,
    string $accessId,
    string $secretKey,
    string $path = null
  ): array {
    $data = [
      "s3_bucket" => $bucket,
      "s3_id" => $accessId,
      "s3_key" => $secretKey,
    ];

    if ($path != null) {
      $data["path"] = $path;
    }
    $response = $this->client->post("ingest/scan", $data);

    return $response->json();
  }

  /**
   * Run ingestion on a given S3 bucket.
   *
   * @param $labelId ID for label that will receive ingested content
   * @param $bucket string	Valid S3 bucket name
   * @param $accessId string	Valid S3 access ID
   * @param $secretKey string	Valid S3 secret key
   * @param $readOnly boolean	If true, will skip file cleanup after ingestion. Default: false
   * @param $path string (optional) Valid S3 path to run ingestion from
   *
   * @return array [success: boolean, ingest_id: integer, label_id: integer, label_name: string, message: string]
   */
  public function run(
    int $labelId,
    string $bucket,
    string $accessId,
    string $secretKey,
    bool $readOnly = false,
    string $path = null
  ): array {
    $data = [
      "label_id" => $labelId,
      "s3_bucket" => $bucket,
      "s3_id" => $accessId,
      "s3_key" => $secretKey,
      "read_only" => $readOnly,
    ];

    if ($path != null) {
      $data["path"] = $path;
    }

    $response = $this->client->post("ingest/run", $data);

    return $response->json();
  }

  /**
   * Get status for a given ingestion.
   *
   * @param $ingestId integer	Valid Ingest ID
   *
   *  @return array [state: string, progress: string, start_time: string, end_time: string, success_count: string, failure_count: string, summary: string]
   */
  public function status(int $ingestId): array
  {
    $data = [
      "ingest_id" => $ingestId,
    ];

    $response = $this->client->get("ingest/status", $data);
    
    if( $response->json() == null){
      Log::info("Empty response from AudioSalad API");
      Log::info($response->status());
      Log::info($response->body());

      return [];
    }

    return $response->json();
  }
}
