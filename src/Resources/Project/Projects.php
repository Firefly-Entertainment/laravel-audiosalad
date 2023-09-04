<?php

namespace KFoobar\Fortnox\Resources\Project;

use KFoobar\Fortnox\Interfaces\ClientInterface;
use KFoobar\Fortnox\Interfaces\ResourceInterface;
use KFoobar\Fortnox\Traits\HasCreate;
use KFoobar\Fortnox\Traits\HasDelete;
use KFoobar\Fortnox\Traits\HasRetrieve;
use KFoobar\Fortnox\Traits\HasUpdate;

class Projects implements ResourceInterface
{
    use HasCreate;
    use HasDelete;
    use HasRetrieve;
    use HasUpdate;

    protected $endpoint = 'projects';

    /**
     * Constructs a new instance.
     *
     * @param \KFoobar\Fortnox\Interfaces\ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }
}
