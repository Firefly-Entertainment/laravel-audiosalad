<?php

namespace StickyBeat\AudioSalad\Traits;

trait HasDelete
{
    /**
     * Sends an delete request.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function delete(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s', $this->endpoint, $id);

        $response = $this->client->delete($endpoint);

        return $response->json();
    }
}
