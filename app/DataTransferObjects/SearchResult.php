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
        public readonly array $affiliate_deals = [],

        public readonly array $cultural_info = [],
        public readonly array $educational_info = [],
        public readonly array $best_time_to_visit = [],
        public readonly array $visa_info = [],

        public readonly array $nearby_destinations = [],
        public readonly array $weather = [],

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
            'success'              => $this->success,
            'city'                 => $this->city,
            'description'          => $this->description,
            'location'             => $this->location,
            'places'               => $this->places,
            'hotels'               => $this->hotels,
            'flights'              => $this->flights,
            'affiliate_deals'      => $this->affiliate_deals,
            'cultural_info'        => $this->cultural_info,
            'educational_info'     => $this->educational_info,
            'best_time_to_visit'   => $this->best_time_to_visit,
            'visa_info'            => $this->visa_info,
            'nearby_destinations'  => $this->nearby_destinations,
            'weather'              => $this->weather,
            'data_quality'         => $this->dataQuality,
            'error'                => $this->error,
            'meta'                 => $this->meta,
        ];
    }

    

private function compressResult(SearchResult $result): string
{
    return gzcompress(serialize($result), 9); // 9 = maximum compression
}

private function decompressResult(string $compressed): SearchResult
{
    return unserialize(gzuncompress($compressed));
}
}