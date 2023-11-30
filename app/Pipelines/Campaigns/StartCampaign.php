<?php

namespace App\Pipelines\Campaigns;

use App\Models\Campaign;
use App\Models\CampaignStatus;

class StartCampaign
{
    /**
     * Mark the campaign as started in the database
     *
     * @return Campaign
     */
    public function handle(Campaign $campaign, $next)
    {
        $this->markCampaignAsSending($campaign);

        return $next($campaign);
    }

    /**
     * Execute the database request
     */
    protected function markCampaignAsSending(Campaign $campaign): ?Campaign
    {
        return tap($campaign)->update([
            'status_id' => CampaignStatus::STATUS_SENDING,
        ]);
    }
}
