<?php

namespace SpiralOver\Nerve\Client;

enum ImpulseReceiverType
{
    case ENDPOINT;
    case RECEPTOR;

    public function getApiValue(): string
    {
        return strtolower($this->name);
    }
}
