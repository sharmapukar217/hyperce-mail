<?php

namespace App\Services\Messages;

use Exception;
use App\Models\EmailService;
use App\Models\Message;
use App\Repositories\Campaigns\CampaignTenantRepositoryInterface;

class ResolveEmailService
{
    /** @var CampaignTenantRepositoryInterface */
    protected $campaignTenantRepository;

    public function __construct(CampaignTenantRepositoryInterface $campaignTenantRepository)
    {
        $this->campaignTenantRepository = $campaignTenantRepository;
    }

    /**
     * @throws Exception
     */
    public function handle(Message $message): EmailService
    {
       
        if ($message->isCampaign()) {
            return $this->resolveCampaignEmailService($message);
        }

        throw new Exception('Unable to resolve email service for message id=' . $message->id);
    }



    /**
     * Resolve the provider for a campaign
     *
     * @param Message $message
     * @return EmailService
     * @throws Exception
     */
    protected function resolveCampaignEmailService(Message $message): EmailService
    {
        if (! $campaign = $this->campaignTenantRepository->find($message->workspace_id, $message->source_id, ['email_service'])) {
            throw new Exception('Unable to resolve campaign for message id=' . $message->id);
        }

        if (! $emailService = $campaign->email_service) {
            throw new Exception('Unable to resolve email service for message id=' . $message->id);
        }

        return $emailService;
    }
}
