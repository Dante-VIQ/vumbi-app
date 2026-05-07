<?php

namespace App\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;

class SearchResult implements Arrayable
{
    public function __construct(
        public readonly bool $success,
        public readonly string $city,
        public readonly ?array $location = null,
        public readonly string $description = '',
        public readonly array $places = [],
        public readonly array $hotels = [],
        public readonly array $flights = [],
        public readonly array $dataQuality = [],
        public readonly ?string $error = null,
        public readonly array $meta = []
    ) {}

    public function needsBuild(): bool
    {
        return $this->dataQuality['can_build'] ?? false;
    }

    public function toArray(): array
    {
        return [
            'success'      => $this->success,
            'city'         => $this->city,
            'description'  => $this->description,
            'location'     => $this->location,
            'places'       => $this->places,
            'hotels'       => $this->hotels,
            'flights'      => $this->flights,
            'data_quality' => $this->dataQuality,
            'error'        => $this->error,
            'meta'         => $this->meta,
        ];
    }
}