<?php

declare(strict_types=1);

namespace Base\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Base\Events\MessageDispatchEvent;
use Base\Services\Messages\DispatchMessage;

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
