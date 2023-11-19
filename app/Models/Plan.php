<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $workspace_id
 * @property int $plan_id
 * @property Carbon|null $enrolled_at
 * @property Carbon|null $expires_at
 *
 * @property PlanType $type
 *
 */
class Plan extends BaseModel
{

    /** @var string */
    protected $table = 'plans';

    /** @var array */
    protected $fillable = [
        'plan_id',
        'workspace_id',
        'enrolled_at',
        'expired_at',
    ];

    /** @var array */
    protected $casts = [
        'id' => 'int',
        'plan_id' => 'int',
        'workspace_id' => 'int'
    ];

    /**
     * The type of this provider.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanType::class, 'plan_id');
    }
}
