<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Subscriber;

use App\Rules\CanAccessTag;
use Illuminate\Foundation\Http\FormRequest;

class SubscriberTagDestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tags' => ['array', 'required'],
            'tags.*' => ['integer', new CanAccessTag($this->user())],
        ];
    }
}
