<?php

namespace App\Models;

class PlanType extends BaseModel
{
    protected $table = 'plan_types';

    public const FREE = 1;
    public const PRO = 2;
    public const PREMIUM = 3;
    public const ENTERPRISE = 4;

    /** @var array */
    protected static $types = [
        self::FREE => 'Free',
        self::PRO => 'Pro',
        self::PREMIUM => 'Premium',
        self::ENTERPRISE => 'Enterprise'
    ];

    /**
     * Resolve a type ID to a type name.
     */
    public static function resolve(int $typeId): ?string
    {
        return static::$types[$typeId] ?? null;
    }
}
