<?php

namespace KFoobar\Fortnox\Resources\FileAttachment;

use KFoobar\Fortnox\Traits\HasCreate;
use KFoobar\Fortnox\Interfaces\ClientInterface;
use KFoobar\Fortnox\Interfaces\ResourceInterface;


class FileAttachments implements ResourceInterface
{
    protected $endpoint = 'attachments-v1';

    /**
     * Constructs a new instance.
     *
     * @param \KFoobar\Fortnox\Interfaces\ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
    * Uploads a file to the inbox.
    *
     */
    public function attach(array $data): mixed
    {

      return $this->client->post($this->endpoint, $data);
    }
}
