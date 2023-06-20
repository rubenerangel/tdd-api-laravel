<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Slug implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->hasUnderscores($value)) {
            // $fail('The slug must not contain underscores.');
            $fail(__('validation.no_underscores'));
        }

        if($this->startsWithDashes($value)) {
            $fail(__('validation.no_starting_with_dashes'));
        }

        if($this->endsWithDashes($value)) {
            $fail(__('validation.no_ending_with_dashes'));
        }
    }

    public function hasUnderscores($value): bool
    {
        return preg_match('/_/', $value);
    }

    public function startsWithDashes($value): bool
    {
        return preg_match('/^-/', $value);
    }

    public function endsWithDashes($value): bool
    {
        return preg_match('/-$/', $value);
    }
}
