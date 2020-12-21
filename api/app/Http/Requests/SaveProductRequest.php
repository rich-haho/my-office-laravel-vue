<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveProductRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $product = $this->route('product');
        $user = $this->user('admin');
        $commissionRequired = $this->request->getBoolean('enable_custom_commission');

        $rule = [
            'names'                    => 'array|required',
            'names.*'                  => 'required|string',
            'descriptions'             => 'array|required',
            'descriptions.*'           => 'string|required',
            'price'                    => 'numeric|required',
            'price_reduction'          => 'numeric|nullable',
            'commission_percentage'    => Rule::requiredIf($commissionRequired),
            'enable_custom_commission' => 'boolean|required',
            'manual_product'           => 'boolean|required',
            'moment_product'           => 'numeric|required',
            'partner_id'               => 'exists:partners,id|required',
            'sites'                    => 'array|required',
            'sites.*'                  => 'required|string',
            'tags'                     => 'array|nullable',
            'tags.*.name'              => 'string',
            'service_id'               => 'exists:services,id|required',
            'currency_id'              => 'exists:currencies,id|required',
            'slots.*.days'             => 'required|numeric|regex:/^[0-1]{7}$/|regex:/[*1*]/',
        ];

        if (!$product) {
            $rule['assets'] = ['required'];
            $rule['assets.*'] = ['image'];
        }

        if ($user->can('manage my products only') && $user->isSuperAdmin() !== true) {
            $rule = [
                'slots.*.days' => 'required|numeric|regex:/^[0-1]{7}$/|regex:/[*1*]/',
            ];
        }

        return $rule;
    }
}
