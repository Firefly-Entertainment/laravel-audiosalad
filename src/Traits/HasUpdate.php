<?php

namespace StickyBeat\AudioSalad\Traits;

trait HasUpdate
{
    /**
     * Sends an update request for given item.
     *
     * @param mixed $id
     * @param array $data
     *
     * @return mixed
     */
    public function update(mixed $id, array $data): mixed
    {
        $endpoint = sprintf('%s/%s', $this->endpoint, $id);

        $response = $this->client->put($endpoint, $data);

        return $response->json();
    }
}
