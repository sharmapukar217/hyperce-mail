<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Container\BindingResolutionException;

class HyperceMail
{
    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @throws BindingResolutionException
     */
    public function setCurrentWorkspaceIdResolver(callable $resolver): void
    {
     $this->app->make('hypercemail.resolver')->setCurrentWorkspaceIdResolver($resolver);
    }

    /**
     * @throws BindingResolutionException
     */
    public function currentWorkspaceId(): ?int
    {
         return $this->app->make('hypercemail.resolver')->resolveCurrentWorkspaceId();
    }

    /**
     * @throws BindingResolutionException
     */
    public function setSidebarHtmlContentResolver(callable $resolver): void
    {
        $this->app->make('hypercemail.resolver')->setSidebarHtmlContentResolver($resolver);
    }

    /**
     * @throws BindingResolutionException
     */
    public function sidebarHtmlContent(): ?string
    {
        return $this->app->make('hypercemail.resolver')->resolveSidebarHtmlContent();
    }

    /**
     * @throws BindingResolutionException
     */
    public function setHeaderHtmlContentResolver(callable $resolver): void
    {
        $this->app->make('hypercemail.resolver')->setHeaderHtmlContentResolver($resolver);
    }

    /**
     * @throws BindingResolutionException
     */
    public function headerHtmlContent(): ?string
    {
        return $this->app->make('hypercemail.resolver')->resolveHeaderHtmlContent();
    }
}
