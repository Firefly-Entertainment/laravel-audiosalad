<?php

namespace StickyBeat\AudioSalad\Resources\Account;

use StickyBeat\AudioSalad\Interfaces\ClientInterface;
use StickyBeat\AudioSalad\Interfaces\ResourceInterface;
use StickyBeat\AudioSalad\Traits\HasCreate;
use StickyBeat\AudioSalad\Traits\HasDelete;
use StickyBeat\AudioSalad\Traits\HasRetrieve;
use StickyBeat\AudioSalad\Traits\HasUpdate;

class Accounts implements ResourceInterface
{
    use HasCreate;
    use HasRetrieve;
    use HasUpdate;

    protected $endpoint = 'accounts';
    protected $client;

    /**
     * Constructs a new instance.
     *
     * @param \StickyBeat\AudioSalad\Interfaces\ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }
}
