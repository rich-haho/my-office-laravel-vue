<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendFmServicesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject'           => 'string|required',
            'description'       => 'string|required',
            'contact'           => 'string|required',
            'building'          => 'string|required',
        ];
    }
}
