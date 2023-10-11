<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignStatus;
use App\Services\Campaigns\CampaignDispatchService;
use App\Repositories\Campaigns\CampaignTenantRepositoryInterface;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class CampaignDispatchCommand extends Command
{
   /** @var string */
    protected $signature = 'app:campaigns:dispatch';

    /** @var string */
    protected $description = 'Dispatch all campaigns waiting in the queue';

    /** @var CampaignTenantRepositoryInterface */
    protected $campaignRepo;

    /** @var CampaignDispatchService */
    protected $campaignService;

    public function handle(
        CampaignTenantRepositoryInterface $campaignRepo,
        CampaignDispatchService $campaignService
    ): void {
        $this->campaignRepo = $campaignRepo;
        $this->campaignService = $campaignService;

        $campaigns = $this->getQueuedCampaigns();
        $count = count($campaigns);

        if (! $count) {
            return;
        }

        $this->info('Dispatching campaigns count=' . $count);

        foreach ($campaigns as $campaign) {
            $message = 'Dispatching campaign id=' . $campaign->id;

            $this->info($message);
            Log::info($message);
            $count++;

            $this->campaignService->handle($campaign);
        }

        $message = 'Finished dispatching campaigns';
        $this->info($message);
        Log::info($message);
    }


   /**
     * Get all queued campaigns.
     */
    protected function getQueuedCampaigns(): Collection
    {
        return Campaign::where('status_id', CampaignStatus::STATUS_QUEUED)
            ->where('scheduled_at', '<=', now())
            ->get();
    }
}
