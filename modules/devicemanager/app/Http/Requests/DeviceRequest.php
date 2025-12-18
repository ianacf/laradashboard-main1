<?php

declare(strict_types=1);

namespace Modules\DeviceManager\Http\Requests;

use App\Http\Requests\FormRequest;

class DeviceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return ld_apply_filters('devices.validation.rules', [
            'device_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'api_key' => 'nullable|string',           
            'status' => 'required|in:pending,enable,disable',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
    }
}

