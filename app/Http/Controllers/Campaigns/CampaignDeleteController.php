<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaigns;

use App\Facades\HyperceMail;
use App\Http\Controllers\Controller;
use App\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignDeleteController extends Controller
{
    /** @var CampaignTenantRepositoryInterface */
    protected $campaigns;

    public function __construct(CampaignTenantRepositoryInterface $campaigns)
    {
        $this->campaigns = $campaigns;
    }

    /**
     * Show a confirmation view prior to deletion.
     *
     * @return RedirectResponse|View
     *
     * @throws Exception
     */
    public function confirm(int $id)
    {
        $campaign = $this->campaigns->find(HyperceMail::currentWorkspaceId(), $id);

        if (! $campaign->draft) {
            return redirect()->route('campaigns.index')
                ->withErrors(__('Unable to delete a campaign that is not in draft status'));
        }

        return view('campaigns.delete', compact('campaign'));
    }

    /**
     * Delete a campaign from the database.
     *
     * @throws Exception
     */
    public function destroy(Request $request): RedirectResponse
    {
        $campaign = $this->campaigns->find(HyperceMail::currentWorkspaceId(), $request->get('id'));

        if (! $campaign->draft) {
            return redirect()->route('campaigns.index')
                ->withErrors(__('Unable to delete a campaign that is not in draft status'));
        }

        $this->campaigns->destroy(HyperceMail::currentWorkspaceId(), $request->get('id'));

        return redirect()->route('campaigns.index')
            ->with('success', __('The Campaign has been successfully deleted'));
    }
}
