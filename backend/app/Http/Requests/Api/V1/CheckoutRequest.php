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
            'contact_phone' => ['required', 'string', 'regex:/^(\+92|0)3\d{9}$/'],
            'address_id' => ['required_without:address', 'nullable', 'uuid'],
            'address' => ['required_without:address_id', 'array'],
            'address.recipient_name' => ['required_with:address', 'string', 'max:120'],
            'address.phone' => ['required_with:address', 'string', 'regex:/^(\+92|0)3\d{9}$/'],
            'address.line1' => ['required_with:address', 'string', 'max:255'],
            'address.line2' => ['nullable', 'string', 'max:255'],
            'address.area' => ['nullable', 'string', 'max:120'],
            'address.city' => ['required_with:address', 'string', 'max:80'],
            'address.postal_code' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contact_phone.regex' => 'Enter a valid Pakistani mobile number, e.g. 03001234567 or +923001234567.',
            'address.phone.regex' => 'Enter a valid Pakistani mobile number for the recipient.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'contact_email' => mb_strtolower(trim((string) $this->input('contact_email'))),
            'contact_phone' => preg_replace('/[\s-]/', '', (string) $this->input('contact_phone')),
        ]);
    }
}
