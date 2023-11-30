<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Tag;

use App\Rules\CanAccessSubscriber;
use Illuminate\Foundation\Http\FormRequest;

class TagSubscriberStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subscribers' => ['array', 'required'],
            'subscribers.*' => ['integer', new CanAccessSubscriber()],
        ];
    }
}
