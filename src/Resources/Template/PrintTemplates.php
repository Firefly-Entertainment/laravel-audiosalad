<?php

namespace KFoobar\Fortnox\Resources\Template;

use KFoobar\Fortnox\Exceptions\FortnoxException;
use KFoobar\Fortnox\Interfaces\ClientInterface;
use KFoobar\Fortnox\Interfaces\ResourceInterface;
use KFoobar\Fortnox\Traits\HasCreate;
use KFoobar\Fortnox\Traits\HasDelete;
use KFoobar\Fortnox\Traits\HasRetrieve;
use KFoobar\Fortnox\Traits\HasUpdate;

class PrintTemplates implements ResourceInterface
{
    use HasRetrieve;

    protected $endpoint = 'printtemplates';

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
     * Retrieve a single print template.
     *
     * @param  mixed                                        $id
     * @throws \KFoobar\Fortnox\Exceptions\FortnoxException
     *
     * @return mixed
     */
    public function get(mixed $id): mixed
    {
        throw new FortnoxException('Print templates do not support this method.');
    }
}
