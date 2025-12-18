<?php

declare(strict_types=1);

namespace Modules\Esp32data\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Esp32ImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can add authorization logic here if needed
        // For now, allowing all authenticated users
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'url' => [
                'required',
                'url',
                'regex:/^https?:\/\//', // Only allow http/https URLs
            ],
            'format' => [
                'sometimes',
                'string',
                'in:json,csv',
            ],
            'mapping' => [
                'sometimes',
                'array',
            ],
            'mapping.sensor' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'mapping.location' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'mapping.value1' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'mapping.value2' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'mapping.value3' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'timeout' => [
                'sometimes',
                'integer',
                'min:5',
                'max:300', // Max 5 minutes
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'url.required' => 'The URL field is required.',
            'url.url' => 'The URL must be a valid URL.',
            'url.regex' => 'The URL must start with http:// or https://.',
            'format.in' => 'The format must be either json or csv.',
            'timeout.min' => 'The timeout must be at least 5 seconds.',
            'timeout.max' => 'The timeout must not exceed 300 seconds.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'url' => 'external URL',
            'format' => 'data format',
            'mapping.sensor' => 'sensor field mapping',
            'mapping.location' => 'location field mapping',
            'mapping.value1' => 'value1 field mapping',
            'mapping.value2' => 'value2 field mapping',
            'mapping.value3' => 'value3 field mapping',
        ];
    }
}