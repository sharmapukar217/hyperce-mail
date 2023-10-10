<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! Hash::check($value, auth()->user()->password)) {
                        $fail('The current password does not match');
                    }
                },
            ],
            'password' => [
                'required',
                'confirmed'
            ]
        ];
    }
}