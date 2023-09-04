<?php

namespace KFoobar\Fortnox\Resources\Voucher;

use KFoobar\Fortnox\Interfaces\ClientInterface;
use KFoobar\Fortnox\Interfaces\ResourceInterface;
use KFoobar\Fortnox\Services\QueryObject;
use KFoobar\Fortnox\Traits\HasCreate;
use KFoobar\Fortnox\Traits\HasDelete;
use KFoobar\Fortnox\Traits\HasRetrieve;
use KFoobar\Fortnox\Traits\HasUpdate;

class Vouchers implements ResourceInterface
{
    use HasCreate;
    use HasRetrieve;

    protected $endpoint = 'vouchers';

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
     * Retrieve a specific voucher.
     *
     * @param mixed                                 $series
     * @param mixed                                 $number
     * @param \KFoobar\Fortnox\Services\QueryObject $query
     *
     * @return mixed
     */
    public function get(mixed $series, mixed $number, QueryObject $query = null): mixed
    {
        $endpoint = sprintf('%s/%s/%s', $this->endpoint, $series, $number);

        return $this->client->get($endpoint, $query->toArray() ?? []);
    }

    /**
     * Retrieve a list of vouchers for a specific series.
     *
     * @param mixed                                 $series
     * @param \KFoobar\Fortnox\Services\QueryObject $query
     *
     * @return mixed
     */
    public function sublist(mixed $series, QueryObject $query = null): mixed
    {
        $endpoint = sprintf('%s/%s', $this->endpoint, $series);

        return $this->client->get($endpoint, $query->toArray() ?? []);
    }
}
