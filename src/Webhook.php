<?php

namespace SpiralOver\Nerve\Client;

readonly class Webhook
{
    public static function capture(string $secret, ?string $hmac = null): Webhook
    {
        $message = file_get_contents('php://input');
        file_put_contents('hmac.txt', $message);
        $calculatedHmac = hash_hmac('sha256', $message, $secret);

        return new Webhook(
            message: $message,
            isVerified: hash_equals($hmac ?? $_SERVER['HTTP_X_NEURON_HEED'], $calculatedHmac)
        );
    }

    public function __construct(
        public string $message,
        public bool   $isVerified
    )
    {
    }
}