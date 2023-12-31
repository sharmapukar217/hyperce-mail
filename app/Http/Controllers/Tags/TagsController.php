<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tags;

use App\Facades\HyperceMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\TagStoreRequest;
use App\Http\Requests\Tags\TagUpdateRequest;
use App\Repositories\TagTenantRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TagsController extends Controller
{
    /** @var TagTenantRepository */
    private $tagRepository;

    public function __construct(TagTenantRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @throws Exception
     */
    public function index(): View
    {
        $tags = $this->tagRepository->paginate(HyperceMail::currentWorkspaceId(), 'name');

        return view('tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('tags.create');
    }

    /**
     * @throws Exception
     */
    public function store(TagStoreRequest $request): RedirectResponse
    {
        $this->tagRepository->store(HyperceMail::currentWorkspaceId(), $request->all());

        return redirect()->route('tags.index');
    }

    /**
     * @throws Exception
     */
    public function edit(int $id): View
    {
        $tag = $this->tagRepository->find(HyperceMail::currentWorkspaceId(), $id, ['subscribers']);

        return view('tags.edit', compact('tag'));
    }

    /**
     * @throws Exception
     */
    public function update(int $id, TagUpdateRequest $request): RedirectResponse
    {
        $this->tagRepository->update(HyperceMail::currentWorkspaceId(), $id, $request->all());

        return redirect()->route('tags.index');
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->tagRepository->destroy(HyperceMail::currentWorkspaceId(), $id);

        return redirect()->route('tags.index');
    }
}
