# Nerve PHP Client

Webhook Payload Router PHP Client

## Getting started

> composer require spiralover/nerve-client

## Usage

### Neuron Management

```php
<?php

use SpiralOver\Nerve\Client\Neuron;

require __DIR__ . '/vendor/autoload.php';

$client  = Neuron::client(pat: '<personal-access-token>');

// List
$neurons = $client->list();

// Create
$created = $client->create(
    name: 'My Neuron 1',
    url: 'localhost:7788',
    webhook: 'localhost:7788/webhook',
    desc: 'Hello World',
);

// Update
$updated = $client->update(
    id: $created->neuron_id,
    name: 'My Neuron 1',
    url: 'localhost:7788',
    webhook: 'localhost:7788/webhook',
    desc: 'Hello World',
);

// Fetch Info
$viewed = $neuron->read('2eb91dc3-b8ad-4d41-a207-963cec055fac');

// Delete
$message = $neuron->delete($created->neuron_id);

```

### Emitting Impulses

Sending impulse(event) to webhooks

```php
<?php

use SpiralOver\Nerve\Client\Neuron;

require __DIR__ . '/vendor/autoload.php';

$client  = Neuron::client(pat: '<personal-access-token>');
$response = $client->emitImpulse(
    neuronId: '2eb91dc3-b8ad-4d41-a207-963cec055fac',
    name: 'user.created',
    data: [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane.doe@example.com'
    ],
    endpoint: 'http://localhost:9999',
    callback: 'http://localhost:7777',
    callbackOnSuccess: false,
    callbackOnFailure: true
);
```

### Receiving Impulses

Receiving impulse(event) from webhook

```php
<?php

use SpiralOver\Nerve\Client\Webhook;

require __DIR__ . '/vendor/autoload.php';

$webhook  = Webhook::capture(secret: '<neuron-secret-key>');
if (!$webhook->isVerified) {    // impulse verification failed
    http_response_code(401);
}

$message = $webhook->message;
```

## Client Options

```php
<?php

use SpiralOver\Nerve\Client\Neuron;

require __DIR__ . '/vendor/autoload.php';

$client  = Neuron::client(
    pat: '<personal-access-token>',
    server: Neuron::SERVER_SPIRALOVER,
    apiVersion: 'v1'
);
```


Enjoy 😎