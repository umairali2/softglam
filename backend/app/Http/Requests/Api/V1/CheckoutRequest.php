<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:32'],
            'address_id' => ['required_without:address', 'nullable', 'uuid'],
            'address' => ['required_without:address_id', 'array'],
            'address.recipient_name' => ['required_with:address', 'string', 'max:120'],
            'address.phone' => ['required_with:address', 'string', 'max:32'],
            'address.line1' => ['required_with:address', 'string', 'max:255'],
            'address.line2' => ['nullable', 'string', 'max:255'],
            'address.area' => ['nullable', 'string', 'max:120'],
            'address.city' => ['required_with:address', 'string', 'max:80'],
            'address.postal_code' => ['nullable', 'string', 'max:20'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'contact_email' => mb_strtolower(trim((string) $this->input('contact_email'))),
        ]);
    }
}
