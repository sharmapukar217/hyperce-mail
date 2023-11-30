<?php

namespace App\Interfaces;

use App\Services\Messages\MessageTrackingOptions;

interface MailAdapterInterface
{
    /**
     * Send an email.
     */
    public function send(string $fromEmail, string $fromName, string $toEmail, string $subject, MessageTrackingOptions $trackingOptions, string $content): string;
}
