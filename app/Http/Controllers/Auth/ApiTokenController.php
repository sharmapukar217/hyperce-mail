<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;
use Exception;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiTokens\ApiTokenStoreRequest;
use App\Repositories\ApiTokenRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use App\Facades\HyperceMail;

class ApiTokenController extends Controller
{
    /** @var ApiTokenRepository */
    private $apiTokensRepo;

    public function __construct(ApiTokenRepository $apiTokensRepo)
    {
        $this->apiTokensRepo = $apiTokensRepo;
    }

    /**
     * @throws Exception
     */
    public function index(): View
    {
        $tokens = $this->apiTokensRepo->all(HyperceMail::currentWorkspaceId());

        return view('api-tokens.index', compact('tokens'));
    }

    /**
     * @throws Exception
     */
    public function store(ApiTokenStoreRequest $request): RedirectResponse
    {
        $input = $request->validated();

        $newToken = Str::random(32);

        $this->apiTokensRepo->store(
            HyperceMail::currentWorkspaceId(),
            ['api_token' => $newToken, 'description' => $input['description']]
        );

        return redirect()
            ->route('api-tokens.index');
    }

    /**
     * @throws Exception
     */
    public function destroy(int $tokenId): RedirectResponse
    {
        $this->apiTokensRepo->destroy(HyperceMail::currentWorkspaceId(), $tokenId);

        return redirect()->back();
    }
}
