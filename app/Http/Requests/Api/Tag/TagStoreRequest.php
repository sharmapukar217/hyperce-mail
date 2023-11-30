<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Tag;

use App\Facades\HyperceMail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('tags')->where('workspace_id', HyperceMail::currentWorkspaceId()),
            ],
            'subscribers' => [
                'array',
                'nullable',
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
