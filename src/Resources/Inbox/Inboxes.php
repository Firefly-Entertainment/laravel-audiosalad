<?php

namespace KFoobar\Fortnox\Resources\Inbox;

use Illuminate\Support\Facades\Log;
use KFoobar\Fortnox\Traits\HasDelete;
use KFoobar\Fortnox\Traits\HasRetrieve;
use KFoobar\Fortnox\Interfaces\ClientInterface;
use KFoobar\Fortnox\Interfaces\ResourceInterface;


class Inboxes implements ResourceInterface
{
    use HasRetrieve;
    use HasDelete;

    protected $endpoint = 'inbox';

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
    * @param string $path - name of the folder to upload to
    * @param string $file - absolute path to the file to upload
    * @param string $fileName - name of the file to upload
     */
    public function upload(string $path, string $file, string $fileName): mixed
    {
        if (!file_exists($file)) {
            throw new \Exception('File does not exist');
        }

        $endpoint = sprintf('%s?path=%s', $this->endpoint, $path);

        return $this->client->upload($endpoint, $file, $fileName);
    }
}
