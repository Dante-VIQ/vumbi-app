<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class BookingFormObject extends Form
{
    // Step 1
    #[Validate('required|date|after:today', message: 'Please choose a start date.')]
    public ?string $start_date = null;

    // Step 2
    #[Validate('required|string|max:100')]
    public ?string $first_name = null;

    #[Validate('required|email')]
    public ?string $email = null;

    #[Validate('required|string|min:10')]
    public ?string $phone = null;


    // Step 3 (optional message, opt‑in)
    public ?string $message = null;

    // Define which fields belong to each step
    protected function stepRules(int $step): array
    {
        return [
            'start_date' => 'required|date|after:today',
            'first_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|min:10',

        ];
    }

}