<?php

namespace SpiralOver\Nerve\Client;

readonly class Webhook
{
    /**
     * @param string $secret
     * @return Webhook
     */
    public static function capture(string $secret): Webhook
    {
        $hash = $_SERVER['HTTP_X_NERVE_IMPULSE_HASH'];
        $message = file_get_contents('php://input');

        return self::captureManual(
            secret: $secret,
            hash: $hash,
            payload: $message,
            reference: $_SERVER['HTTP_X_NERVE_IMPULSE_REFERENCE']
        );
    }

    /**
     * @param string $secret
     * @param string $hash
     * @param string $payload
     * @param string $reference
     * @return Webhook
     */
    public static function captureManual(string $secret, string $hash, string $payload, string $reference): Webhook
    {
        $calculatedHmac = hash_hmac('sha256', $payload, $secret);

        return new Webhook(
            message: $payload,
            hash: $hash,
            reference: $reference,
            isVerified: hash_equals($hash, $calculatedHmac)
        );
    }

    public function __construct(
        public string $message,
        public string $hash,
        public string $reference,
        public bool   $isVerified
    )
    {
    }
}