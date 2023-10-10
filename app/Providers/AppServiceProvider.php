<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

use App\Services\Helper;
use App\Services\HyperceMail;
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
            return $app->make(HyperceMail::class);
       });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
