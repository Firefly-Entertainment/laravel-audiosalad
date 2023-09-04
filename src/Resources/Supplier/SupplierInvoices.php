<?php

namespace KFoobar\Fortnox\Resources\Supplier;

use KFoobar\Fortnox\Interfaces\ClientInterface;
use KFoobar\Fortnox\Interfaces\ResourceInterface;
use KFoobar\Fortnox\Traits\HasCreate;
use KFoobar\Fortnox\Traits\HasDelete;
use KFoobar\Fortnox\Traits\HasRetrieve;
use KFoobar\Fortnox\Traits\HasUpdate;

class SupplierInvoices implements ResourceInterface
{
    use HasCreate;
    use HasRetrieve;
    use HasUpdate;

    protected $endpoint = 'supplierinvoices';

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
     * Bookkeep given supplier invoice.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function bookkeep(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/bookkeep', $this->endpoint, $id);

        return $this->client->put($endpoint);
    }

    /**
     * Cancels given supplier invoice.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function cancel(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/cancel', $this->endpoint, $id);

        return $this->client->put($endpoint);
    }

    /**
     * Credit given supplier invoice.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function credit(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/credit', $this->endpoint, $id);

        return $this->client->put($endpoint);
    }

    /**
     * Approval of payment of given supplier invoice.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function approve(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/approvalpayment', $this->endpoint, $id);

        return $this->client->put($endpoint);
    }
}
