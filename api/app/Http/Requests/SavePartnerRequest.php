<?php

namespace App\Http\Requests;

use App\Rules\ValidStripeAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePartnerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                => 'string|required',
            'address'             => 'string|required',
            'phone'               => 'string|required',
            'email'               => 'string|email|required',
            'contact_name'        => 'string|required',
            'commission_percentage' => Rule::requiredIf($this->request->get('enable_stripe_connect')),
            'connected_stripe_id' => [
                Rule::requiredIf($this->request->get('enable_stripe_connect')),
                $this->request->get('enable_stripe_connect') ? new ValidStripeAccount() : ''
            ]
        ];
    }
}
