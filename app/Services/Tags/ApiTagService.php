<?php

declare(strict_types=1);

namespace App\Services\Tags;

use App\Models\Tag;
use App\Repositories\TagTenantRepository;
use Exception;
use Illuminate\Support\Collection;

class ApiTagService
{
    /** @var TagTenantRepository */
    private $tags;

    public function __construct(TagTenantRepository $tags)
    {
        $this->tags = $tags;
    }

    /**
     * Store a new tag, optionally including attached subscribers.
     *
     * @throws Exception
     */
    public function store(int $workspaceId, Collection $data): Tag
    {
        $tag = $this->tags->store($workspaceId, $data->except('subscribers')->toArray());

        if (! empty($data['subscribers'])) {
            $tag->subscribers()->attach($data['subscribers']);
        }

        return $tag;
    }
}
