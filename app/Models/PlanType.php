<?php

namespace App\Models;

class PlanType extends BaseModel
{
    protected $table = 'plan_types';

    public const FREE = 1;
    public const BASIC = 2;
    public const PRO = 3;

    /** @var array */
    protected static $types = [
        self::FREE => 'Free',
        self::BASIC => 'Basic',
        self::PRO => 'Pro',
    ];

    /**
     * Resolve a type ID to a type name.
     */
    public static function resolve(int $typeId): ?string
    {
        return static::$types[$typeId] ?? null;
    }
}
