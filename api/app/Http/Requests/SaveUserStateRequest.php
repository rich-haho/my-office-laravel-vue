<?php

namespace App\Http\Requests;

use App\Models\Site;
use Illuminate\Foundation\Http\FormRequest;

class SaveUserStateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'locale_id'     => ['required', 'exists:locales,id'],
            'site_id'       => ['required', 'exists:sites,id'],
        ];
    }
}
