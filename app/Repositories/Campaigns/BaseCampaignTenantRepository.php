<?php

declare(strict_types=1);

namespace App\Repositories\Campaigns;

use App\Models\Campaign;
use App\Models\CampaignStatus;
use App\Repositories\BaseTenantRepository;
use App\Traits\SecondsToHms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class BaseCampaignTenantRepository extends BaseTenantRepository implements CampaignTenantRepositoryInterface
{
    use SecondsToHms;

    /** @var string */
    protected $modelName = Campaign::class;

    /**
     * {@inheritDoc}
     */
    public function completedCampaigns(int $workspaceId, array $relations = [], int $take=4): EloquentCollection
    {
        return $this->getQueryBuilder($workspaceId)
            ->where('status_id', CampaignStatus::STATUS_SENT)
            ->with($relations)
            ->take($take)
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getCounts(Collection $campaignIds, int $workspaceId): array
    {
        $counts = DB::table('campaigns')
            ->leftJoin('messages', function ($join) use ($campaignIds, $workspaceId) {
                $join->on('messages.source_id', '=', 'campaigns.id')
                    ->where('messages.source_type', Campaign::class)
                    ->whereIn('messages.source_id', $campaignIds)
                    ->where('messages.workspace_id', $workspaceId);
            })
            ->select('campaigns.id as campaign_id')
            ->selectRaw(sprintf('count(%smessages.id) as total', DB::getTablePrefix()))
            ->selectRaw(sprintf('count(case when %smessages.opened_at IS NOT NULL then 1 end) as opened', DB::getTablePrefix()))
            ->selectRaw(sprintf('count(case when %smessages.clicked_at IS NOT NULL then 1 end) as clicked', DB::getTablePrefix()))
            ->selectRaw(sprintf('count(case when %smessages.sent_at IS NOT NULL then 1 end) as sent', DB::getTablePrefix()))
            ->selectRaw(sprintf('count(case when %smessages.bounced_at IS NOT NULL then 1 end) as bounced', DB::getTablePrefix()))
            ->selectRaw(sprintf('count(case when %smessages.sent_at IS NULL then 1 end) as pending', DB::getTablePrefix()))
            ->groupBy('campaigns.id')
            ->orderBy('campaigns.id')
            ->get();

        return $counts->flatten()->keyBy('campaign_id')->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function cancelCampaign(Campaign $campaign): bool
    {
        $this->deleteDraftMessages($campaign);

        return $campaign->update([
            'status_id' => CampaignStatus::STATUS_CANCELLED,
        ]);
    }

    private function deleteDraftMessages(Campaign $campaign): void
    {
        if (! $campaign->save_as_draft) {
            return;
        }

        $campaign->messages()->whereNull('sent_at')->delete();
    }

    /**
     * {@inheritDoc}
     */
    protected function applyFilters(Builder $instance, array $filters = []): void
    {
        $this->applySentFilter($instance, $filters);
    }

    /**
     * Filter by sent status.
     */
    protected function applySentFilter(Builder $instance, array $filters = []): void
    {
        if (Arr::get($filters, 'draft')) {
            $draftStatuses = [
                CampaignStatus::STATUS_DRAFT,
                CampaignStatus::STATUS_QUEUED,
                CampaignStatus::STATUS_SENDING,
            ];

            $instance->whereIn('status_id', $draftStatuses);
        } elseif (Arr::get($filters, 'sent')) {
            $sentStatuses = [
                CampaignStatus::STATUS_SENT,
                CampaignStatus::STATUS_CANCELLED,
            ];

            $instance->whereIn('status_id', $sentStatuses);
        }
    }
}
