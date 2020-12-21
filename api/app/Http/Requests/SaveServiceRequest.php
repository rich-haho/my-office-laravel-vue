<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $service = $this->route('service');
        $rule = [
            'names'               => 'array|required',
            'names.*'             => 'required|string',
            'descriptions'        => 'array|required',
            'descriptions.*'      => 'required|string',
            'external_link'       => 'nullable|url',
        ];
        if (!$service) {
            $rule['assets'] = ['required'];
            $rule['assets.*'] = ['image'];
        }
        return $rule;
    }
}
