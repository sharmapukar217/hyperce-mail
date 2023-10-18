<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Tag;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CanAccessSubscriber;

class TagSubscriberStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subscribers' => ['array', 'required'],
            'subscribers.*' => ['integer', new CanAccessSubscriber()]
        ];
    }
}
