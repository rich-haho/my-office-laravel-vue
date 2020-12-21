<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveEmailDomainRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'domain'        => 'string|required|unique:email_domains',
        ];
    }
    public function messages()
    {
        return [
            'string'        => 'Le domaine doit être une chaîne.',
            'required'      => 'Le domaine est obligatoire.',
            'unique'        => 'Ce domaine est déjà utilisé',
        ];
    }
}
