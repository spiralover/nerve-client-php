<?php

namespace SpiralOver\Nerver\Client;

trait ClientTrait
{
    private ?string $proxy;
    private ?array $postData;
    private ?string $neuronId;
    private float $timeout = 2.0;
    private array $query = [];
    private array $headers = [];


    protected function withTimeout(float $timeout): static
    {
        $this->timeout = $timeout;
        return $this;
    }

    protected function withHeaders(array $headers): static
    {
        $this->headers = $headers;
        return $this;
    }

    protected function withQuery(array $params): static
    {
        $this->query = $params;
        return $this;
    }

    protected function withData(array $data): static
    {
        $this->postData = $data;
        return $this;
    }

    /**
     * You can provide proxy URLs that contain a scheme, username, and password. For example, "http://username:password@192.168.16.1:10"
     *
     * @param string $addr
     * @return $this
     */
    protected function withProxy(string $addr): static
    {
        $this->proxy = $addr;
        return $this;
    }

    protected function getClientOptions(): array
    {
        $data = [
            'timeout' => $this->timeout,
            'query' => $this->query,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->pat,
                ...$this->headers,
            ],
        ];

        if (isset($this->proxy)) {
            $data['proxy'] = $this->proxy;
        }

        if (isset($this->postData)) {
            $data['json'] = $this->postData;
        }

        return $data;
    }
}