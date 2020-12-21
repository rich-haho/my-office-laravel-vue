<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSiteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'string|required',
            'client_id'     => 'exists:clients,id|required',
            'open_time'     => 'string|required',
            'phone_number'  => 'string|required|min:9',
            'address'       => 'string|nullable',
        ];
    }
}
