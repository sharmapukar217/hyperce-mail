<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaigns;

use App\Facades\HyperceMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Campaigns\CampaignTestRequest;
use App\Services\Messages\DispatchTestMessage;
use Exception;
use Illuminate\Http\RedirectResponse;

class CampaignTestController extends Controller
{
    /** @var DispatchTestMessage */
    protected $dispatchTestMessage;

    public function __construct(DispatchTestMessage $dispatchTestMessage)
    {
        $this->dispatchTestMessage = $dispatchTestMessage;
    }

    /**
     * @throws Exception
     */
    public function handle(CampaignTestRequest $request, int $campaignId): RedirectResponse
    {
        $messageId = $this->dispatchTestMessage->handle(HyperceMail::currentWorkspaceId(), $campaignId, $request->get('recipient_email'));

        if (! $messageId) {
            return redirect()->route('campaigns.preview', $campaignId)
                ->withInput()
                ->with(['error', __('Failed to dispatch test email.')]);
        }

        return redirect()->route('campaigns.preview', $campaignId)
            ->withInput()
            ->with(['success' => __('The test email has been dispatched.')]);
    }
}
