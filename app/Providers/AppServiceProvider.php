<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\UserEnrollmentService;
use App\Services\CoreBanking\HttpClientInterface;
use App\Services\CoreBanking\GuzzleHttpClient;
use App\Contracts\VAS\AccessBankApiInterface;
use App\Contracts\VAS\CyclosTransferInterface;
use App\Services\VAS\AccessBankApiService;
use App\Services\VAS\CyclosTransferService;
use App\Repositories\PaymentAlertRepository;
use App\Services\Government\Contracts\HttpClientInterface as GovHttpClientInterface;
use App\Services\Government\Http\LaravelHttpClient;
use App\Services\Government\Contracts\GovernmentServiceInterface;
use App\Services\Government\CbhiService;
use App\Services\Government\LtssServiceRefactored;
use App\Services\Government\RraTaxServiceRefactored;
use App\Services\Government\RraTaxCollectionHandler;
use App\Services\Utilities\RraIntegration;
use App\Services\CoreBanking\UsersAccess;
use GuzzleHttp\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind existing services
        $this->app->bind(HttpClientInterface::class, function ($app) {
            return new GuzzleHttpClient(new Client());
        });
        $this->app->bind(UserEnrollmentService::class, function ($app) {
            return new UserEnrollmentService($app->make(HttpClientInterface::class));
        });

        // VAS (Value Added Services) dependency injection bindings
        // These interfaces allow for loose coupling and easier testing/mocking
        $this->app->bind(AccessBankApiInterface::class, AccessBankApiService::class); // Access Bank API integration
        $this->app->bind(CyclosTransferInterface::class, CyclosTransferService::class); // Cyclos fund transfer operations
        $this->app->bind(PaymentAlertRepository::class, PaymentAlertRepository::class); // Payment data persistence

        // Government services dependency injection bindings
        $this->app->bind(GovHttpClientInterface::class, LaravelHttpClient::class);
        $this->app->bind(RraTaxCollectionHandler::class, RraTaxCollectionHandler::class);

        // Bind CBHI service
        $this->app->bind(CbhiService::class, function ($app) {
            return new CbhiService(
                $app->make(GovHttpClientInterface::class),
                'https://testbox.mobicash.rw/mobicore/api'
            );
        });

        // Bind LTSS service
        $this->app->bind(LtssServiceRefactored::class, function ($app) {
            return new LtssServiceRefactored(
                $app->make(GovHttpClientInterface::class),
                'https://api.ltss.rw' // Example base URL
            );
        });

        // Bind RRA Tax service
        $this->app->bind(RraTaxServiceRefactored::class, function ($app) {
            return new RraTaxServiceRefactored(
                $app->make(GovHttpClientInterface::class),
                'https://api.rra.rw', // Example base URL
                $app->make(RraIntegration::class),
                $app->make(UsersAccess::class),
                $app->make(RraTaxCollectionHandler::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
