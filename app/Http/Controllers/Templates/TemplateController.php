<?php

declare(strict_types=1);

namespace App\Http\Controllers\Templates;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Facades\HyperceMail;
use App\Http\Requests\TemplateStoreRequest;
use App\Http\Requests\TemplateUpdateRequest;
use App\Repositories\TemplateTenantRepository;
use App\Services\Templates\TemplateService;
use App\Traits\NormalizeTags;
use Throwable;

class TemplatesController extends Controller
{
    use NormalizeTags;

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
    public function index(): View
    {
        $templates = $this->templates->paginate(HyperceMail::currentWorkspaceId(), 'name');

        return view('templates.index', compact('templates'));
    }

    public function create(): View
    {
        return view('templates.create');
    }

    /**
     * @throws Exception
     */
    public function store(TemplateStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->service->store(currentWorkspaceId(), $data);

        return redirect()
            ->route('sendportal.templates.index');
    }

    /**
     * @throws Exception
     */
    public function edit(int $id): View
    {
        $template = $this->templates->find(currentWorkspaceId(), $id);

        return view('templates.edit', compact('template'));
    }

    /**
     * @throws Exception
     */
    public function update(TemplateUpdateRequest $request, int $id): RedirectResponse
    {
        $data = $request->validated();

        $this->service->update(currentWorkspaceId(), $id, $data);

        return redirect()
            ->route('sendportal.templates.index');
    }

    /**
     * @throws Throwable
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete(currentWorkspaceId(), $id);

        return redirect()
            ->route('sendportal.templates.index')
            ->with('success', __('Template successfully deleted.'));
    }
}