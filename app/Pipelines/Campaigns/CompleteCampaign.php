<?php

namespace App\Pipelines\Campaigns;

use App\Models\Campaign;
use App\Models\CampaignStatus;

class CompleteCampaign
{
    /**
     * Mark the campaign as complete in the database
     *
     * @return Campaign
     */
    public function handle(Campaign $schedule, $next)
    {
        $this->markCampaignAsComplete($schedule);

        return $next($schedule);
    }

    /**
     * Execute the database query
     */
    protected function markCampaignAsComplete(Campaign $campaign): void
    {
        $campaign->status_id = CampaignStatus::STATUS_SENT;
        $campaign->save();
    }
}
