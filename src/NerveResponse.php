<?php

namespace SpiralOver\Nerve\Client;

use Psr\Http\Message\ResponseInterface;
use SpiralOver\Nerve\Client\Exceptions\RequestFailureException;

class NerveResponse
{
    protected mixed $decodedResponse = [];

    /**
     * @param ResponseInterface $response
     * @return static
     * @throws RequestFailureException
     */
    public static function new(ResponseInterface $response): static
    {
        return new static($response);
    }

    /**
     * @param ResponseInterface $response
     * @throws RequestFailureException
     */
    public function __construct(
        public readonly ResponseInterface $response
    )
    {
        $this->decodedResponse = json_decode($response->getBody()->getContents(), true);

        if ($this->hasFailed()) {
            throw new RequestFailureException($this->message());
        }
    }

    /**
     * Check if this request has failed
     *
     * @return bool
     */
    public function hasFailed(): bool
    {
        return !$this->decodedResponse['success'];
    }

    /**
     * Check if this request has succeeded
     *
     * @return bool
     */
    public function hasSucceeded(): bool
    {
        return !$this->hasFailed();
    }

    public function message(): ?string
    {
        return $this->decodedResponse['message'];
    }

    public function status(): int
    {
        return $this->decodedResponse['status'] ?? 500;
    }

    public function data(): mixed
    {
        return $this->decodedResponse['data'] ?? null;
    }

    public function records(): mixed
    {
        return $this->data()['records'] ?? [];
    }
}