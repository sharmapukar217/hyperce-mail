<?php

declare(strict_types=1);

namespace App\Traits;

trait SecondsToHms
{
    /**
     * @param  int|string  $seconds
     */
    protected function secondsToHms($seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor($seconds / 60) % 60;
        $seconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
