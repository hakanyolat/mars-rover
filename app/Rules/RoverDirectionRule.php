<?php

namespace App\Rules;

use App\Helpers\Direction;
use Illuminate\Contracts\Validation\Rule;

class RoverDirectionRule implements Rule
{
    public function __construct(){}

    public function passes($attribute, $value): bool {
        return in_array($value, Direction::All);
    }

    public function message(): string {
        return 'Invalid rover direction.';
    }
}
