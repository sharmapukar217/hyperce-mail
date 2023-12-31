<?php

namespace App\Services;

use Carbon\Carbon;

class Helper
{
    /**
     * Display a given date in the active user's timezone.
     *
     * @param  mixed  $date
     * @return mixed
     */
    public function displayDate($date, string $timezone = null)
    {
        if (! $date) {
            return null;
        }

        return Carbon::parse($date)->copy()->setTimezone($timezone);
    }

    public function isPro(): bool
    {
        return false;
    }
}
