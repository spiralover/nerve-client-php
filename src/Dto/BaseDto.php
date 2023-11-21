<?php

namespace SpiralOver\Nerve\Client\Dto;

abstract readonly class BaseDto
{
    public static function from(array $data): static
    {
        return new static(...$data);
    }
}