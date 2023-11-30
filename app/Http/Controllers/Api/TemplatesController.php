<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Facades\HyperceMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Template\TemplateStoreRequest;
use App\Http\Requests\Api\Template\TemplateUpdateRequest;
use App\Http\Resources\Template as TemplateResource;
use App\Repositories\TemplateTenantRepository;
use App\Services\Templates\TemplateService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TemplatesController extends Controller
{
    /** @var TemplateTenantRepository */
    private $templates;

    /** @var TemplateService */
    private $service;

    public function __construct(TemplateTenantRepository $templates, TemplateService $service)
    {
        $this->templates = $templates;
        $this->service = $service;
    }

    /**
     * @throws Exception
     */
    public function index(): AnonymousResourceCollection
    {
        $workspaceId = HyperceMail::currentWorkspaceId();
        $templates = $this->templates->paginate($workspaceId, 'name');

        return TemplateResource::collection($templates);
    }

    /**
     * @throws Exception
     */
    public function show(int $id): TemplateResource
    {
        $workspaceId = HyperceMail::currentWorkspaceId();

        return new TemplateResource($this->templates->find($workspaceId, $id));
    }

    /**
     * @throws Exception
     */
    public function store(TemplateStoreRequest $request): TemplateResource
    {
        $workspaceId = HyperceMail::currentWorkspaceId();
        $template = $this->service->store($workspaceId, $request->validated());

        return new TemplateResource($template);
    }

    /**
     * @throws Exception
     */
    public function update(TemplateUpdateRequest $request, int $id): TemplateResource
    {
        $workspaceId = HyperceMail::currentWorkspaceId();
        $template = $this->service->update($workspaceId, $id, $request->validated());

        return new TemplateResource($template);
    }

    /**
     * @throws \Throwable
     */
    public function destroy(int $id): Response
    {
        $workspaceId = HyperceMail::currentWorkspaceId();
        $this->service->delete($workspaceId, $id);

        return response(null, 204);
    }
}
