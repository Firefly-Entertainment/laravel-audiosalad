<?php

namespace KFoobar\Fortnox\Resources\Invoice;

use KFoobar\Fortnox\Interfaces\ClientInterface;
use KFoobar\Fortnox\Interfaces\ResourceInterface;
use KFoobar\Fortnox\Traits\HasCreate;
use KFoobar\Fortnox\Traits\HasDelete;
use KFoobar\Fortnox\Traits\HasRetrieve;
use KFoobar\Fortnox\Traits\HasUpdate;

class Invoices implements ResourceInterface
{
    use HasCreate;
    use HasRetrieve;
    use HasUpdate;

    protected $endpoint = 'invoices';

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
     * Bookkeep given invoice.
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
     * Cancels given invoice.
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
     * Credit given invoice.
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
     * Set given invoice as sent.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function sent(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/externalprint', $this->endpoint, $id);

        return $this->client->put($endpoint);
    }

    /**
     * Set given invoice as done.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function done(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/warehouseready', $this->endpoint, $id);

        return $this->client->put($endpoint);
    }

    /**
     * Prints given invoice.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function print(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/print', $this->endpoint, $id);

        return $this->client->get($endpoint);
    }

    /**
     * Sends given invoice as email.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function email(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/email', $this->endpoint, $id);

        return $this->client->get($endpoint);
    }

    /**
     * Prints given invoice as reminder.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function reminder(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/printreminder', $this->endpoint, $id);

        return $this->client->get($endpoint);
    }

    /**
     * Preview given invoice.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function preview(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/preview', $this->endpoint, $id);

        return $this->client->get($endpoint);
    }

    /**
     * Sends given invoice as e-print.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function eprint(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/eprint', $this->endpoint, $id);

        return $this->client->get($endpoint);
    }

    /**
     * Sends given invoice as e-invoice.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function einvoice(mixed $id): mixed
    {
        $endpoint = sprintf('%s/%s/einvoice', $this->endpoint, $id);

        return $this->client->get($endpoint);
    }
}
