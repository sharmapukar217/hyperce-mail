<?php

namespace App\Providers;

use App\Livewire\Setup;
use App\Models\ApiToken;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

use RuntimeException;
use Livewire\Livewire;


use App\Services\Helper;
use App\Services\HyperceMail as HyperceMailService;
use App\Facades\HyperceMail;
use App\Services\QuotaService;
use App\Providers\ResolverProvider;
use App\Traits\ResolvesDatabaseDriver;
use App\Interfaces\QuotaServiceInterface;
use App\Providers\EventServiceProvider;
use App\Providers\FormServiceProvider;
use App\Repositories\Campaigns\MySqlCampaignTenantRepository;
use App\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use App\Repositories\Campaigns\PostgresCampaignTenantRepository;
use App\Repositories\Messages\MessageTenantRepositoryInterface;
use App\Repositories\Messages\MySqlMessageTenantRepository;
use App\Repositories\Messages\PostgresMessageTenantRepository;
use App\Repositories\Subscribers\MySqlSubscriberTenantRepository;
use App\Repositories\Subscribers\PostgresSubscriberTenantRepository;
use App\Repositories\Subscribers\SubscriberTenantRepositoryInterface;


class AppServiceProvider extends ServiceProvider
{
    use ResolvesDatabaseDriver;

    /**
     * Register any application services.
     */
    public function register(): void
    {
         // Campaign repository.
         $this->app->bind(CampaignTenantRepositoryInterface::class, function (Application $app) {
            if ($this->usingPostgres()) {
                return $app->make(PostgresCampaignTenantRepository::class);
            }

            return $app->make(MySqlCampaignTenantRepository::class);
        });

        // Message repository.
        $this->app->bind(MessageTenantRepositoryInterface::class, function (Application $app) {
            if ($this->usingPostgres()) {
                return $app->make(PostgresMessageTenantRepository::class);
            }

            return $app->make(MySqlMessageTenantRepository::class);
        });

        // Subscriber repository.
        $this->app->bind(SubscriberTenantRepositoryInterface::class, function (Application $app) {
            if ($this->usingPostgres()) {
                return $app->make(PostgresSubscriberTenantRepository::class);
            }

            return $app->make(MySqlSubscriberTenantRepository::class);
        });

        $this->app->bind(QuotaServiceInterface::class, QuotaService::class);

        $this->app->singleton('hypercemail.helper', function () {
            return new Helper();
        });

        // Providers.
        $this->app->register(EventServiceProvider::class);
        $this->app->register(FormServiceProvider::class);
        $this->app->register(ResolverProvider::class);

         // Facade.
         $this->app->bind('hypercemail', static function (Application $app) {
            return $app->make(HyperceMailService::class);
       });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        HyperceMail::setCurrentWorkspaceIdResolver(
            static function () {
                /** @var User $user */
                $user = auth()->user();
                $request = request();
                $workspaceId = null;

                if ($user && $user->currentWorkspaceId()) {
                    $workspaceId = $user->currentWorkspaceId();
                } else if ($request && (($apiToken = $request->bearerToken()) || ($apiToken = $request->get('api_token')))) {
                    $workspaceId = ApiToken::resolveWorkspaceId($apiToken);
                }

                if (! $workspaceId) {
                    throw new RuntimeException("Current Workspace ID Resolver must not return a null value.");
                }

                return $workspaceId;
            }
        );

        HyperceMail::setSidebarHtmlContentResolver(
            static function () {
                return view('layouts.sidebar.manageUsersMenuItem')->render();
            }
        );

        HyperceMail::setHeaderHtmlContentResolver(
            static function () {
                return view('layouts.header.userManagementHeader')->render();
            }
        );

        Livewire::component('setup', Setup::class);
    }
}
