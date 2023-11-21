<?php

namespace SpiralOver\Nerve\Client\Dto;

readonly class NeuronDto extends BaseDto
{
    public function __construct(
        public string $neuron_id,
        public string $created_by,
        public string $name,
        public string $code,
        public string $url,
        public string $logo,
        public string $webhook,
        public string $description,
        public string $status,
        public string $created_at,
        public string $updated_at,
        public ?string $deleted_at,
    ){}
}