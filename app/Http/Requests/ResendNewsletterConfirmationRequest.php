<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResendNewsletterConfirmationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'newsletter_company_website' => [
                'nullable',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if ($value !== null && ! is_string($value)) {
                        $fail(__('The :attribute field must be a string.'));
                    }
                    if (filled($value)) {
                        $fail(__('Invalid submission.'));
                    }
                },
            ],
        ];
    }
}
