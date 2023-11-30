<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Facades\HyperceMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tag\TagSubscriberDestroyRequest;
use App\Http\Requests\Api\Tag\TagSubscriberStoreRequest;
use App\Http\Requests\Api\Tag\TagSubscriberUpdateRequest;
use App\Http\Resources\Subscriber as SubscriberResource;
use App\Repositories\TagTenantRepository;
use App\Services\Tags\ApiTagSubscriberService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagSubscribersController extends Controller
{
    /** @var TagTenantRepository */
    private $tags;

    /** @var ApiTagSubscriberService */
    private $apiService;

    public function __construct(
        TagTenantRepository $tags,
        ApiTagSubscriberService $apiService
    ) {
        $this->tags = $tags;
        $this->apiService = $apiService;
    }

    /**
     * @throws Exception
     */
    public function index(int $tagId): AnonymousResourceCollection
    {
        $workspaceId = HyperceMail::currentWorkspaceId();
        $tag = $this->tags->find($workspaceId, $tagId, ['subscribers']);

        return SubscriberResource::collection($tag->subscribers);
    }

    /**
     * @throws Exception
     */
    public function store(TagSubscriberStoreRequest $request, int $tagId): AnonymousResourceCollection
    {
        $input = $request->validated();
        $workspaceId = HyperceMail::currentWorkspaceId();
        $subscribers = $this->apiService->store($workspaceId, $tagId, collect($input['subscribers']));

        return SubscriberResource::collection($subscribers);
    }

    /**
     * @throws Exception
     */
    public function update(TagSubscriberUpdateRequest $request, int $tagId): AnonymousResourceCollection
    {
        $input = $request->validated();
        $workspaceId = HyperceMail::currentWorkspaceId();
        $subscribers = $this->apiService->update($workspaceId, $tagId, collect($input['subscribers']));

        return SubscriberResource::collection($subscribers);
    }

    /**
     * @throws Exception
     */
    public function destroy(TagSubscriberDestroyRequest $request, int $tagId): AnonymousResourceCollection
    {
        $input = $request->validated();
        $workspaceId = HyperceMail::currentWorkspaceId();
        $subscribers = $this->apiService->destroy($workspaceId, $tagId, collect($input['subscribers']));

        return SubscriberResource::collection($subscribers);
    }
}
