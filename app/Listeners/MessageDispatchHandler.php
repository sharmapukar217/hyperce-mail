<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\MessageDispatchEvent;
use App\Services\Messages\DispatchMessage;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageDispatchHandler implements ShouldQueue
{
    /** @var string */
    public $queue = 'message-dispatch';

    /** @var DispatchMessage */
    protected $dispatchMessage;

    public function __construct(DispatchMessage $dispatchMessage)
    {
        $this->dispatchMessage = $dispatchMessage;
    }

    /**
     * @throws Exception
     */
    public function handle(MessageDispatchEvent $event): void
    {
        $this->dispatchMessage->handle($event->message);
    }
}
