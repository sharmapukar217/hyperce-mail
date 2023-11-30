<?php

declare(strict_types=1);

namespace App\Http\Requests\Tags;

use App\Facades\HyperceMail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('tags')
                    ->where('workspace_id', HyperceMail::currentWorkspaceId())
                    ->ignore($this->tag),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => __('The tag name must be unique.'),
        ];
    }
}
