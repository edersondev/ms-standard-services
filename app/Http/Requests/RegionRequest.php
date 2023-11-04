<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name'          => ['required', 'string', 'max:80'],
            'region_code'   => ['nullable', 'max:3'],
            'country_id'    => ['required', 'exists:tb_country,id']
        ];

        if (in_array(['PUT', 'PATCH'], [$this->route()->methods()])) {
            array_unshift($rules['name'], 'sometimes');
            array_unshift($rules['country_id'], 'sometimes');
        }

        return $rules;
    }
}
