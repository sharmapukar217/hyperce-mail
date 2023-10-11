<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ApiToken;

class ApiTokenRepository extends BaseTenantRepository
{
    /** @var string */
    protected $modelName = ApiToken::class;
}
