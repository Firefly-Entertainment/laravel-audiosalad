<?php

namespace StickyBeat\AudioSalad\Resources\Label;

use Illuminate\Support\Carbon;
use StickyBeat\AudioSalad\Services\Client;
use StickyBeat\AudioSalad\Interfaces\ResourceInterface;
use StickyBeat\AudioSalad\Exceptions\AudioSaladException;

class Labels implements ResourceInterface
{
  protected $client;

  public function __construct(Client $client)
  {
    $this->client = $client;
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
  public function get(int $labelId = null, string $labelName = null): mixed
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
