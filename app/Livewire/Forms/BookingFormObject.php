<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class BookingFormObject extends Form
{
    // Step 1
    #[Validate('required|date|after:today', message: 'Please choose a start date.')]
    public ?string $start_date = null;

    public ?string $end_date = null;

    #[Validate('required|integer|min:1')]
    public int $adults = 2;

    public int $children = 0;

    public ?string $special_occasion = null;

    // Step 2
    #[Validate('required|string|max:100')]
    public ?string $first_name = null;

    #[Validate('required|string|max:100')]
    public ?string $last_name = null;

    #[Validate('required|email')]
    public ?string $email = null;

    #[Validate('required|string|min:10')]
    public ?string $phone = null;

    #[Validate('required|string|size:2')]
    public string $country = 'KE';

    // Step 3 (optional message, opt‑in)
    public ?string $message = null;
    public bool $whatsapp_opt_in = true;

    // Define which fields belong to each step
    protected function stepRules(int $step): array
    {
        return match ($step) {
            1 => ['start_date', 'end_date', 'adults', 'children', 'special_occasion'],
            2 => ['first_name', 'last_name', 'email', 'phone', 'country'],
            3 => [], // all validated on final submit
            default => [],
        };
    }

public function validateStep(int $step)
{
    $fields = $this->stepRules($step);
    foreach ($fields as $field) {
        $this->validateOnly($field);
    }
}
}