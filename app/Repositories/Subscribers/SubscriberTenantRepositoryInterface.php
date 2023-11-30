<?php

declare(strict_types=1);

namespace App\Repositories\Subscribers;

use App\Interfaces\BaseTenantInterface;
use App\Models\Subscriber;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

interface SubscriberTenantRepositoryInterface extends BaseTenantInterface
{
    public function syncTags(Subscriber $subscriber, array $tags = []);

    public function countActive($workspaceId): int;

    public function getRecentSubscribers(int $workspaceId): Collection;

    public function getGrowthChartData(CarbonPeriod $period, int $workspaceId): array;
}
