<?php

declare(strict_types=1);

namespace App\Rules;

use App\Facades\HyperceMail;
use App\Models\Subscriber;
use Illuminate\Contracts\Validation\Rule;

class CanAccessSubscriber implements Rule
{
    public function passes($attribute, $value): bool
    {
        $subscriber = Subscriber::find($value);

        if (! $subscriber) {
            return false;
        }

        return $subscriber->workspace_id == HyperceMail::currentWorkspaceId();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
