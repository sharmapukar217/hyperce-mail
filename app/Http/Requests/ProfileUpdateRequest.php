<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $this->user()->id,
            ],
            'locale' => [
                'required',
                Rule::in(array_keys(config()->get('config.locale.supported')))
            ],
        ];
    }
}