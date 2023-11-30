<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Webhooks;

use App\Events\Webhooks\PostmarkWebhookReceived;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PostmarkWebhooksController extends Controller
{
    public function handle(): Response
    {
        /** @var array $payload */
        $payload = json_decode(request()->getContent(), true);

        Log::info('Postmark webhook received');

        event(new PostmarkWebhookReceived($payload));

        return response('OK');
    }
}
