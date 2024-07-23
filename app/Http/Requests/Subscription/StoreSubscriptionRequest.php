<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'renewal_at' => 'required|date_format:Y-m-d H:i:s',
        ];
    }

    /**
     * Customize the error messages for the validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'renewal_at.required' => 'The renewal_at field is required.',
            'renewal_at.datetime' => 'renewal_at field date format is invalid. Y-m-d H:i:s',
        ];
    }
}
