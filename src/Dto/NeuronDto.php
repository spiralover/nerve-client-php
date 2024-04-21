<?php

namespace SpiralOver\Nerve\Client\Dto;

readonly class NeuronDto extends BaseDto
{
    public function __construct(
        public string  $neuron_id,
        public string  $user_id,
        public string  $name,
        public string  $unique_name,
        public string  $slug,
        public string  $visibility,
        public string  $url,
        public string  $logo,
        public string  $webhook,
        public string  $description,
        public string  $status,
        public string  $created_at,
        public string  $updated_at,
        public ?string $deleted_at,
    ){}
}