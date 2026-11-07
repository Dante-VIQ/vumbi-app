<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResponse extends Model
{
    protected $fillable = ['persona', 'interest', 'budget', 'days', 'subscribed'];

    protected $casts = ['subscribed' => 'boolean'];
}
