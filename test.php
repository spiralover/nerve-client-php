<?php

use GuzzleHttp\Exception\GuzzleException;
use SpiralOver\Nerve\Client\Exceptions\RequestFailureException;
use SpiralOver\Nerve\Client\Neuron;

require __DIR__ . '/vendor/autoload.php';

$token = 'nerve_pat_eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJiZTZlZTczNi1lZDRkLTQzYzktOWM5MS1iZmQwMzE4Yjg3NWUiLCJpYXQiOjE3MDA1MDg3MTYsImV4cCI6MTg5MzI4MTc1Nn0.MwOKjh60x76Bmm_HaG2PJA-Be4ajGNV3oS6Yskx5VZo';
$neuron  = Neuron::client(pat: $token);

try {
    // List Neurons
    $neurons = $neuron->list();

    // Create Neuron
    $created = $neuron->create(
        name: 'My Neuron',
        url: 'localhost:7788',
        webhook: 'localhost:7788/webhook',
        desc: 'Hello World',
    );

    // Create Neuron
    $updated = $neuron->update(
        id: $created->neuron_id,
        name: 'My Neuron 1',
        url: 'localhost:7788',
        webhook: 'localhost:7788/webhook',
        desc: 'Hello World',
    );

    // View Info
    $neuronInfo = $neuron->read('2eb91dc3-b8ad-4d41-a207-963cec055fac');

    // Delete
    $deleted = $neuron->delete($created->neuron_id);

    // Emit

    var_dump($created);
    var_dump($updated);
    var_dump($deleted);
} catch (GuzzleException|RequestFailureException $e) {
    echo $e;
}
