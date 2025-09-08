<?php

namespace Firefly\AudioSalad\Resources\Image;

use Illuminate\Support\Carbon;
use Firefly\AudioSalad\Services\Client;
use Firefly\AudioSalad\Interfaces\ResourceInterface;
use Firefly\AudioSalad\Exceptions\AudioSaladException;

class Images implements ResourceInterface
{
  public const XSMALL = "xsmall";
  public const SMALL = "small";
  public const MEDIUM = "medium";
  public const LARGE = "large";
  public const XLARGE = "xlarge";
  public const XXLARGE = "xxlarge";

  protected $client;

  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  /**
   * Get an image and store it to disk
   *
   * @param $imageId integer	Valid Image ID
   * @param $size string	Valid Image Size "xxsmall" : 90px, "xsmall" : 125px, "small" : 175px, "medium" : 300px, "large" : 640px, "xlarge" : 1024px, or "xxlarge" : 2048px
   * @param $file string	Where to store the image
   *
   * @return bool
   */

  public function get(int $imageId, string $size, mixed $file): bool
  {
    // Make sure size is valid
    if (
      $size != self::XSMALL &&
      $size != self::SMALL &&
      $size != self::MEDIUM &&
      $size != self::LARGE &&
      $size != self::XLARGE &&
      $size != self::XXLARGE
    ) {
      throw new AudioSaladException("Invalid size");
    }

    $this->client->sink("image", [
      "image_id" => $imageId,
      "size" => $size,
    ], filter: [], file: $file);

    return true;

  }
}
