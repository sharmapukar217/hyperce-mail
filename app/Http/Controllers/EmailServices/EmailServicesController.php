<?php

declare(strict_types=1);

namespace App\Http\Controllers\EmailServices;

use App\Facades\HyperceMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailServices\EmailServiceRequest;
use App\Repositories\EmailServiceTenantRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailServicesController extends Controller
{
    /** @var EmailServiceTenantRepository */
    private $emailServices;

    public function __construct(EmailServiceTenantRepository $emailServices)
    {
        $this->emailServices = $emailServices;
    }

    /**
     * @throws Exception
     */
    public function index(): View
    {
        $emailServices = $this->emailServices->all(HyperceMail::currentWorkspaceId());

        return view('email_services.index', compact('emailServices'));
    }

    public function create(): View
    {
        $emailServiceTypes = $this->emailServices->getEmailServiceTypes()->pluck('name', 'id');

        return view('email_services.create', compact('emailServiceTypes'));
    }

    /**
     * @throws Exception
     */
    public function store(EmailServiceRequest $request): RedirectResponse
    {
        $emailServiceType = $this->emailServices->findType($request->type_id);

        $settings = $request->get('settings', []);

        $this->emailServices->store(HyperceMail::currentWorkspaceId(), [
            'name' => $request->name,
            'type_id' => $emailServiceType->id,
            'settings' => $settings,
        ]);

        return redirect()->route('email_services.index');
    }

    /**
     * @throws Exception
     */
    public function edit(int $emailServiceId)
    {
        $emailServiceTypes = $this->emailServices->getEmailServiceTypes()->pluck('name', 'id');
        $emailService = $this->emailServices->find(HyperceMail::currentWorkspaceId(), $emailServiceId);
        $emailServiceType = $this->emailServices->findType($emailService->type_id);

        return view('email_services.edit', compact('emailServiceTypes', 'emailService', 'emailServiceType'));
    }

    /**
     * @throws Exception
     */
    public function update(EmailServiceRequest $request, int $emailServiceId): RedirectResponse
    {
        $emailService = $this->emailServices->find(HyperceMail::currentWorkspaceId(), $emailServiceId, ['type']);

        $settings = $request->get('settings');

        $emailService->name = $request->name;
        $emailService->settings = $settings;
        $emailService->save();

        return redirect()->route('email_services.index');
    }

    /**
     * @throws Exception
     */
    public function delete(int $emailServiceId): RedirectResponse
    {
        $emailService = $this->emailServices->find(HyperceMail::currentWorkspaceId(), $emailServiceId, ['campaigns']);

        if ($emailService->in_use) {
            return redirect()->back()->withErrors(__('You cannot delete an email service that is currently used by a campaign or automation.'));
        }

        $this->emailServices->destroy(HyperceMail::currentWorkspaceId(), $emailServiceId);

        return redirect()->route('email_services.index');
    }

    public function emailServicesTypeAjax($emailServiceTypeId): JsonResponse
    {
        $emailServiceType = $this->emailServices->findType($emailServiceTypeId);

        $view = view()
            ->make('email_services.options.'.strtolower($emailServiceType->name))
            ->render();

        return response()->json([
            'view' => $view,
        ]);
    }
}
