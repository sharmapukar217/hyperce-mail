<?php

namespace App\Http\Controllers\EmailServices;

use App\Facades\HyperceMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailServices\EmailServiceTestRequest;
use App\Repositories\EmailServiceTenantRepository;
use App\Services\Messages\DispatchTestMessage;
use App\Services\Messages\MessageOptions;
use Exception;
use Illuminate\Http\RedirectResponse;

class TestEmailServiceController extends Controller
{
    /** @var EmailServiceTenantRepository */
    private $emailServices;

    public function __construct(EmailServiceTenantRepository $emailServices)
    {
        $this->emailServices = $emailServices;
    }

    public function create(int $emailServiceId)
    {
        $emailService = $this->emailServices->find(HyperceMail::currentWorkspaceId(), $emailServiceId);

        return view('email_services.test', compact('emailService'));
    }

    /**
     * @throws Exception
     */
    public function store(int $emailServiceId, EmailServiceTestRequest $request, DispatchTestMessage $dispatchTestMessage): RedirectResponse
    {
        $emailService = $this->emailServices->find(HyperceMail::currentWorkspaceId(), $emailServiceId);

        $options = new MessageOptions();
        $options->setFromEmail($request->input('from'));
        $options->setSubject($request->input('subject'));
        $options->setTo($request->input('to'));
        $options->setBody($request->input('body'));

        try {
            $messageId = $dispatchTestMessage->testService(HyperceMail::currentWorkspaceId(), $emailService, $options);

            if (! $messageId) {
                return redirect()
                    ->back()
                    ->with(['error', __('Failed to dispatch test email.')]);
            }

            return redirect()
                ->route('email_services.index')
                ->with(['success' => __('The test email has been dispatched.')]);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Response: '.$e->getMessage());
        }
    }
}
