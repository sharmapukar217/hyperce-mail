<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Subscriber;

use Illuminate\Foundation\Http\FormRequest;

class SubscriberStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['nullable'],
            'last_name' => ['nullable'],
            'email' => ['required', 'email'],
            'tags' => ['array', 'nullable'],
            'unsubscribed_at' => ['nullable', 'date'],
        ];
    }
}
