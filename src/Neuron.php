<?php

namespace SpiralOver\Nerve\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SpiralOver\Nerve\Client\Dto\NeuronDto;
use SpiralOver\Nerve\Client\Exceptions\RequestFailureException;

class Neuron
{
    use ClientTrait;

    public const API_VERSION_1_0 = 'v1';
    public const SERVER_LOCAL = 'http://localhost:4303';
    public const SERVER_DOCKER = 'http://192.168.87.1:4303';
    public const SERVER_SPIRALOVER = 'https://nerve.spiralover.com';


    private readonly Client $client;


    /**
     * @param string $pat Personal Access Token
     * @param string $server Server Web Address
     * @param string $apiVersion
     * @return static
     */
    public static function client(
        string $pat,
        string $server,
        string $apiVersion = Neuron::API_VERSION_1_0,
    ): Neuron
    {
        return new static(pat: $pat, server: $server, apiVersion: $apiVersion);
    }

    public function __construct(
        private readonly string $pat,
        private readonly string $server,
        private readonly string $apiVersion,
    )
    {
        $this->client = new Client([
            'base_uri' => sprintf('%s/api/%s/', $this->server, $this->apiVersion),
        ]);
    }

    /**
     * @return NeuronDto[]
     * @throws RequestFailureException
     * @throws GuzzleException
     */
    public function list(): array
    {
        $response = NerveResponse::new(response: $this->client->get(
            uri: 'neurons',
            options: $this->getClientOptions(),
        ));

        $neurons = [];
        foreach ($response->data() as $neuron) {
            $neurons[] = NeuronDto::from($neuron);
        }

        return $neurons;
    }

    /**
     * @param string $id
     * @return NeuronDto
     * @throws GuzzleException
     * @throws RequestFailureException
     */
    public function read(string $id): NeuronDto
    {
        $response = NerveResponse::new(response: $this->client->get(
            uri: sprintf('neurons/%s', $id),
            options: $this->getClientOptions(),
        ));

        return NeuronDto::from($response->data());
    }

    /**
     * @param string $id
     * @return string
     * @throws GuzzleException
     * @throws RequestFailureException
     */
    public function delete(string $id): string
    {
        $response = NerveResponse::new(response: $this->client->delete(
            uri: sprintf('neurons/%s', $id),
            options: $this->getClientOptions(),
        ));

        return $response->message();
    }

    /**
     * @param string $name
     * @param string $url
     * @param string $webhook
     * @param string $desc
     * @return NeuronDto
     * @throws GuzzleException
     * @throws RequestFailureException
     */
    public function create(string $name, string $url, string $webhook, string $desc): NeuronDto
    {
        $this->withData([
            'name' => $name,
            'url' => $url,
            'webhook' => $webhook,
            'description' => $desc,
        ]);

        $response = NerveResponse::new(response: $this->client->post(
            uri: 'neurons',
            options: $this->getClientOptions(),
        ));

        return NeuronDto::from($response->data());
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $url
     * @param string $webhook
     * @param string $desc
     * @return NeuronDto
     * @throws GuzzleException
     * @throws RequestFailureException
     */
    public function update(string $id, string $name, string $url, string $webhook, string $desc): NeuronDto
    {
        $this->withData([
            'name' => $name,
            'url' => $url,
            'webhook' => $webhook,
            'description' => $desc,
        ]);

        $response = NerveResponse::new(response: $this->client->put(
            uri: sprintf('neurons/%s', $id),
            options: $this->getClientOptions(),
        ));

        return NeuronDto::from($response->data());
    }

    /**
     * @param string $neuronId
     * @param string $name Event name
     * @param array|object $data Event data
     * @param string $endpoint Payload receiving endpoint
     * @param string $uniqueReference
     * @param string|null $callback
     * @param string|null $secretKey Hash event with this secret key
     * @param ImpulseReceiverType $receiverType Type of this event receiver
     * @param bool $callbackOnSuccess Weather to call you back when the webhook accept this impulse
     * @param bool $callbackOnFailure Weather to call you back when the webhook reject this impulse or throws error handling it
     * @return NerveResponse
     * @throws GuzzleException
     * @throws RequestFailureException
     */
    public function emitImpulse(
        string              $neuronId,
        string              $name,
        array|object        $data,
        string              $endpoint,
        string              $uniqueReference,
        ?string             $callback = null,
        ?string             $secretKey = null,
        ImpulseReceiverType $receiverType = ImpulseReceiverType::ENDPOINT,
        bool                $callbackOnSuccess = false,
        bool                $callbackOnFailure = false,
    ): NerveResponse
    {
        $this->withData([
            'endpoint' => $endpoint,
            'callback' => $callback,
            'reference' => $uniqueReference,
            'secret_key' => $secretKey,
            'receiver_type' => $receiverType->getApiValue(),
            'impulse_name' => $name,
            'impulse_data' => $data,
            'callback_on_success' => $callbackOnSuccess,
            'callback_on_failure' => $callbackOnFailure,
        ]);

        return NerveResponse::new(response: $this->client->post(
            uri: sprintf('neurons/%s/emit-impulse', $neuronId),
            options: $this->getClientOptions(),
        ));
    }
}