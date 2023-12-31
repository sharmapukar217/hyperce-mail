<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\EmailServiceFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $workspace_id
 * @property string|null $name
 * @property int $type_id
 * @property array $settings
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property EmailServiceType $type
 * @property Collection $campaigns
 *
 * @method static EmailServiceFactory factory
 */
class EmailService extends BaseModel
{
    protected static function newFactory()
    {
        return EmailServiceFactory::new();
    }

    /** @var string */
    protected $table = 'email_services';

    /** @var array */
    protected $fillable = [
        'name',
        'type_id',
        'settings',
    ];

    /** @var array */
    protected $casts = [
        'id' => 'int',
        'workspace_id' => 'int',
        'type_id' => 'int',
    ];

    /**
     * The type of this provider.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(EmailServiceType::class, 'type_id');
    }

    /**
     * Campaigns using this provider.
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'email_service_id');
    }

    /**
     * Automations using this email service.
     */
    public function automations(): HasMany
    {
        return $this->hasMany(Automation::class, 'email_service_id');
    }

    public function setSettingsAttribute(array $data): void
    {
        $this->attributes['settings'] = encrypt(json_encode($data));
    }

    public function getSettingsAttribute(string $value): array
    {
        return json_decode(decrypt($value), true);
    }

    public function getInUseAttribute(): bool
    {
        return (bool) $this->campaigns()->count();
    }
}
