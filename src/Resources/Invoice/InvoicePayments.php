<?php

namespace KFoobar\Fortnox\Resources\Invoice;

use KFoobar\Fortnox\Interfaces\ClientInterface;
use KFoobar\Fortnox\Interfaces\ResourceInterface;
use KFoobar\Fortnox\Traits\HasCreate;
use KFoobar\Fortnox\Traits\HasDelete;
use KFoobar\Fortnox\Traits\HasRetrieve;
use KFoobar\Fortnox\Traits\HasUpdate;

class InvoicePayments implements ResourceInterface
{
    use HasCreate;
    use HasDelete;
    use HasRetrieve;
    use HasUpdate;

    protected $endpoint = 'invoicepayments';

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
     * Bookkeep given invoice payment.
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
}
