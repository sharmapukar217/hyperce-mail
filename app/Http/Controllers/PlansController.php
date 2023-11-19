<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Plan;
use App\Models\PlanType;
use App\Facades\HyperceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PlansController extends Controller
{

    private function getAvailablePlans() {
       return  PlanType::select('id', 'plan as name')
        ->pluck('name', 'id');
    }
    
    private function getCurrentPlan() {
        $workspace_id = HyperceMail::currentWorkspaceId();
        return Plan::where("workspace_id", $workspace_id)->first();
    }

    /**
     * @throws Exception
     */
    public function show(): View
    {
        return view('plans.index', [
            "currentPlan" => $this->getCurrentPlan(),
            "availablePlans" => $this->getAvailablePlans()
        ]);
    }


    public function update(Request $request): View {
        $currentDate = Carbon::now();
        $selectedPlanId = $request->get('plan_id');
        $workspaceId = HyperceMail::currentWorkspaceId();

        // TODO: ADD EXPIRY DATE FROM FRONTEND
        Plan::updateOrInsert(["workspace_id"=>$workspaceId],[
            "plan_id" => $selectedPlanId,
            "enrolled_at" => $currentDate,
            "expires_at" => $currentDate->addDays(14),
        ]);

        return view('plans.index', [
            "currentPlan" => $this->getCurrentPlan(),
            "availablePlans" => $this->getAvailablePlans()
        ]);
    }

}