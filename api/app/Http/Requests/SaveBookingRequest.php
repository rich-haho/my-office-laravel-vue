<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveBookingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'        => 'exists:users,id|required',
            'product_id'     => 'exists:booking_products,id|required',
            'date'           => 'required',
            'quantity'       => 'numeric|required',
        ];
    }
}
