<?php

namespace StickyBeat\AudioSalad\Traits;

trait HasCreate
{
    /**
     * Sends an create request.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data): mixed
    {
        $response = $this->client->post($this->endpoint, $data);

        return $response->json();
    }
}
