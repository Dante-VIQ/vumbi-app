<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Doctor;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;

class ApplicationForm extends Form
{
    #[Rule('required|min:3|max:200')]
    public $name;

    #[Rule('required|email|max:255')]
    public $email;

    #[Rule('required|number|0-9')]
    public $mobile;
}
