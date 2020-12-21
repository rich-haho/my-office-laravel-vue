<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveAdminRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $admin = $this->route('admin');
        $password = $admin ? null : 'required|min:8|same:confirm_password';

        $uniqueRule = Rule::unique('admins', 'email')->where(function ($query) {
            return $query->where('deleted_at', null);
        });
        $rule_data = [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                $admin ? $uniqueRule->ignore($admin) : $uniqueRule
            ],
            'role_id' => 'required_unless:admin,true'
        ];
        if ($password) {
            $rule_data['password'] = $password;
        }
        return $rule_data;
    }
}
