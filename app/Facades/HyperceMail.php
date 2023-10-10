<?php

declare(strict_types=1);

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void setCurrentWorkspaceIdResolver(callable $resolver)
 * @method static int|null currentWorkspaceId
 * @method static void setSidebarHtmlContentResolver(callable $resolver)
 * @method static string|null sidebarHtmlContent
 * @method static void setHeaderHtmlContentResolver(callable $resolver)
 * @method static string|null headerHtmlContent
 */

class HyperceMail extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'hypercemail';
    }
}
