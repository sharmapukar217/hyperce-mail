<?php

declare(strict_types=1);

namespace App\Services\Subscribers\Tags;

use App\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use Exception;
use Illuminate\Support\Collection;

class ApiSubscriberTagService
{
    /** @var SubscriberTenantRepositoryInterface */
    private $subscribers;

    public function __construct(SubscriberTenantRepositoryInterface $subscribers)
    {
        $this->subscribers = $subscribers;
    }

    /**
     * Add tags to a subscriber.
     *
     *
     * @throws Exception
     */
    public function store(int $workspaceId, int $subscriberId, Collection $tagIds): Collection
    {
        $subscriber = $this->subscribers->find($workspaceId, $subscriberId);

        /** @var Collection $existingTags */
        $existingTags = $subscriber->tags()->pluck('tag.id')->toBase();

        $tagsToStore = $tagIds->diff($existingTags);

        $subscriber->tags()->attach($tagsToStore);

        return $subscriber->tags->toBase();
    }

    /**
     * Sync the list of tags a subscriber is associated with.
     *
     *
     * @throws Exception
     */
    public function update(int $workspaceId, int $subscriberId, Collection $tagIds): Collection
    {
        $subscriber = $this->subscribers->find($workspaceId, $subscriberId, ['tags']);

        $subscriber->tags()->sync($tagIds);

        $subscriber->load('tags');

        return $subscriber->tags->toBase();
    }

    /**
     * Remove tags from a subscriber.
     *
     *
     * @throws Exception
     */
    public function destroy(int $workspaceId, int $subscriberId, Collection $tagIds): Collection
    {
        $subscriber = $this->subscribers->find($workspaceId, $subscriberId);

        $subscriber->tags()->detach($tagIds);

        return $subscriber->tags;
    }
}
