<?php

namespace App\Http\Requests\Templates;

use App\Facades\HyperceMail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('templates')
                    ->where('workspace_id', HyperceMail::currentWorkspaceId())
                    ->ignore($this->template),
            ],
            'content' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => __('The template name must be unique.'),
        ];
    }
}
