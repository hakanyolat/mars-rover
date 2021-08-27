<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PlateauHeightRule implements Rule
{
    const Limit = 50;

    public function __construct(){}

    public function passes($attribute, $value): bool
    {
        return $value >= 0 && $value <= self::Limit;
    }

    public function message(): string
    {
        return sprintf('The Plateau height(x) must be lower than %s.', self::Limit);
    }
}
