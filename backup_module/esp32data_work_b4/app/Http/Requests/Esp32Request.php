<?php

declare(strict_types=1);

namespace Modules\Esp32data\Http\Requests;

use App\Http\Requests\FormRequest;

class Esp32Request extends FormRequest
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
        return ld_apply_filters('esp32s.validation.rules', [
            'sensor' => 'required|string',
            'location' => 'required|string',
            'value1' => 'required|string',
            'value2' => 'required|string',
			'value3' => 'required|string',
        ]);
    }
}
