<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SaveRatingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id'     => [
                Rule::unique('ratings', 'product_id')->where('user_id', Auth::user()->id),
                'required'
            ],
            'rate'           => 'numeric|between:1,5|required',
        ];
    }
}
