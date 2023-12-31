<?php

declare(strict_types=1);

namespace App\Services\Webhooks;

use App\Models\Message;
use App\Models\MessageFailure;
use App\Models\MessageUrl;
use App\Models\UnsubscribeEventType;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailWebhookService
{
    public function handleDelivery(string $messageId, Carbon $timestamp): void
    {
        DB::table('messages')->where('message_id', $messageId)->whereNull('delivered_at')->update([
            'delivered_at' => $timestamp,
        ]);
    }

    /**
     * @throws Exception
     */
    public function handleOpen(string $messageId, Carbon $timestamp, ?string $ipAddress): void
    {
        /** @var Message $message */
        $message = Message::where('message_id', $messageId)->first();

        if (! $message) {
            return;
        }

        if (! $message->opened_at) {
            $message->opened_at = $timestamp;
            $message->ip = $ipAddress;
        }

        $message->open_count++;
        $message->save();
    }

    /**
     * @throws Exception
     */
    public function handleClick(string $messageId, Carbon $timestamp, ?string $url): void
    {
        /* @var Message $message */
        $message = Message::where('message_id', $messageId)->first();

        if (! $message) {
            return;
        }

        // Don't track unsubscribe clicks.
        if (Str::contains($url, '/subscriptions/unsubscribe')) {
            return;
        }

        if (! $message->clicked_at) {
            $message->clicked_at = $timestamp;
        }

        // Since you have to open a campaign to click a link inside it, we'll consider those clicks as opens
        // even if the tracking image didn't load.
        if (! $message->opened_at) {
            $message->open_count++;
            $message->opened_at = $timestamp;
        }

        $message->click_count++;
        $message->save();

        $messageUrlHash = $this->generateMessageUrlHash($message, $url);

        if ($messageUrl = MessageUrl::where('hash', $messageUrlHash)->first()) {
            $messageUrl->update([
                'click_count' => $messageUrl->click_count + 1,
            ]);
        } else {
            MessageUrl::create([
                'hash' => $messageUrlHash,
                'source_type' => $message->source_type,
                'source_id' => $message->source_id,
                'url' => $url,
                'click_count' => 1,
            ]);
        }
    }

    public function handleComplaint(string $messageId, Carbon $timestamp): void
    {
        /* @var Message $message */
        $message = Message::where('message_id', $messageId)->first();

        if (! $message) {
            return;
        }

        if (! $message->complained_at) {
            $message->unsubscribed_at = $timestamp;
            $message->save();
        }

        $this->unsubscribe($messageId, UnsubscribeEventType::COMPLAINT);
    }

    public function handlePermanentBounce($messageId, $timestamp): void
    {
        /* @var Message $message */
        $message = Message::where('message_id', $messageId)->first();

        if (! $message) {
            return;
        }

        if (! $message->bounced_at) {
            $message->bounced_at = $timestamp;
            $message->save();
        }

        $this->unsubscribe($messageId, UnsubscribeEventType::BOUNCE);
    }

    public function handleFailure($messageId, $severity, $description, $timestamp): void
    {
        /* @var Message $message */
        $message = Message::where('message_id', $messageId)->first();

        if (! $message) {
            return;
        }

        $failure = new MessageFailure([
            'severity' => $severity,
            'description' => $description,
            'failed_at' => $timestamp,
        ]);

        $message->failures()->save($failure);
    }

    /**
     * Unsubscribe a subscriber.
     */
    protected function unsubscribe(string $messageId, int $typeId): void
    {
        $subscriberId = DB::table('messages')->where('message_id', $messageId)->value('subscriber_id');

        if (! $subscriberId) {
            return;
        }

        DB::table('subscribers')->where('id', $subscriberId)->update([
            'unsubscribed_at' => now(),
            'unsubscribe_event_id' => $typeId,
            'updated_at' => now(),
        ]);
    }

    protected function generateMessageUrlHash(Message $message, string $url): string
    {
        return md5($message->source_type.'_'.$message->source_id.'_'.$url);
    }
}
