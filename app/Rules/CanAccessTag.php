<?php

declare(strict_types=1);

namespace App\Rules;

use App\Facades\HyperceMail;
use App\Models\Tag;
use Illuminate\Contracts\Validation\Rule;

class CanAccessTag implements Rule
{
    public function passes($attribute, $value): bool
    {
        $tag = Tag::find($value);

        if (! $tag) {
            return false;
        }

        return $tag->workspace_id == HyperceMail::currentWorkspaceId();
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'Tag ID :input does not exist.';
    }
}
