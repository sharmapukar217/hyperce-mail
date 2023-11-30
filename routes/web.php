<?php

use App\Http\Middleware\OwnsCurrentWorkspace;
use App\Http\Middleware\RequireValidPlan;
use App\Http\Middleware\RequireWorkspace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'reset' => config('config.auth.password_reset'),
    'verify' => config('config.auth.register', false),
    'register' => config('config.auth.register', true),
]);

Route::view('/', 'index');
Route::get('setup', 'SetupController@index')->name('setup');

Route::middleware(['verified', RequireWorkspace::class])->name('plans.')->prefix('plans')->group(static function () {
    Route::get('/', 'PlansController@show')->name('show');
    Route::post('/update', 'PlansController@update')->name('update');
});

// Auth.
Route::middleware('auth')->namespace('Auth')->group(static function () {
    // Logout.
    Route::get('logout', 'LoginController@logout');

    // Profile
    Route::middleware('verified')->name('profile.')->prefix('profile')->group(static function () {
        Route::get('/', 'ProfileController@show')->name('show');
        Route::get('/edit', 'ProfileController@edit')->name('edit');
        Route::put('/', 'ProfileController@update')->name('update');

        // Password
        Route::name('password.')->prefix('password')->group(static function () {
            Route::get('/edit', 'ChangePasswordController@edit')->name('edit');
            Route::put('/', 'ChangePasswordController@update')->name('update');
        });
    });

    // Api Tokens
    Route::middleware('verified')->name('api-tokens.')
        ->prefix('api-tokens')->group(static function () {
            Route::get('/', 'ApiTokenController@index')->name('index');
            Route::post('/', 'ApiTokenController@store')->name('store');
            Route::delete('{tokenid}', 'ApiTokenController@destroy')->name('destroy');
        });
});

Route::middleware(['auth', 'verified', RequireValidPlan::class])
    ->prefix('dashboard')
    ->group(static function () {
        // dashboard
        Route::get('/', 'DashboardController@index')->name('dashboard');

        // Workspace Management.
        Route::resource('workspaces', 'Workspaces\WorkspacesController')->except(['create', 'show', 'destroy']);

        // Workspace Switching.
        Route::get('workspaces/{workspace}/switch', 'Workspaces\SwitchWorkspaceController@switch')
            ->name('workspaces.switch');

        // Workspace User Management.
        Route::middleware(['auth', 'verified', RequireWorkspace::class, OwnsCurrentWorkspace::class])
            ->name('users.')
            ->prefix('users')
            ->group(static function () {
                Route::get('/', 'Workspaces\WorkspaceUsersController@index')->name('index');
                Route::delete('{userId}', 'Workspaces\WorkspaceUsersController@destroy')->name('destroy');

                // Invitations.
                Route::name('invitations.')->prefix('invitations')
                    ->group(static function () {
                        Route::post('/', 'Workspaces\WorkspaceInvitationsController@store')->name('store');
                        Route::delete('{invitation}', 'Workspaces\WorkspaceInvitationsController@destroy')
                            ->name('destroy');
                    });
            });

        // Invitations.
        Route::post('workspaces/invitations/{invitation}/accept', 'Workspaces\PendingInvitationController@accept')
            ->name('workspaces.invitations.accept');
        Route::post('workspaces/invitations/{invitation}/reject', 'Workspaces\PendingInvitationController@reject')
            ->name('workspaces.invitations.reject');

        // Campaigns
        Route::resource('campaigns', "Campaigns\CampaignsController")->except(['show', 'destroy']);
        Route::name('campaigns.')->prefix('campaigns')->namespace('Campaigns')->group(static function () {
            Route::get('sent', 'CampaignsController@sent')->name('sent');
            Route::get('{id}', 'CampaignsController@show')->name('show');
            Route::get('{id}/preview', 'CampaignsController@preview')->name('preview');
            Route::put('{id}/send', 'CampaignDispatchController@send')->name('send');
            Route::get('{id}/status', 'CampaignsController@status')->name('status');
            Route::post('{id}/test', 'CampaignTestController@handle')->name('test');

            Route::get('{id}/confirm-delete', 'CampaignDeleteController@confirm')->name('destroy.confirm');
            Route::delete('', 'CampaignDeleteController@destroy')->name('destroy');

            Route::get('{id}/duplicate', 'CampaignDuplicateController@duplicate')->name('duplicate');

            Route::get('{id}/confirm-cancel', 'CampaignCancellationController@confirm')->name('confirm-cancel');
            Route::post('{id}/cancel', 'CampaignCancellationController@cancel')->name('cancel');

            Route::get('{id}/report', 'CampaignReportsController@index')->name('reports.index');
            Route::get('{id}/report/recipients', 'CampaignReportsController@recipients')->name('reports.recipients');
            Route::get('{id}/report/opens', 'CampaignReportsController@opens')->name('reports.opens');
            Route::get('{id}/report/clicks', 'CampaignReportsController@clicks')->name('reports.clicks');
            Route::get('{id}/report/unsubscribes', 'CampaignReportsController@unsubscribes')->name('reports.unsubscribes');
            Route::get('{id}/report/bounces', 'CampaignReportsController@bounces')->name('reports.bounces');
        });

        // Tags
        Route::resource('tags', "Tags\TagsController")->except(['show']);

        // Templates
        Route::resource('templates', "Templates\TemplatesController");

        // Subscribers.
        Route::name('subscribers.')->prefix('subscribers')->namespace('Subscribers')->group(static function () {
            Route::get('export', 'SubscribersController@export')->name('export');
            Route::get('import', 'SubscribersImportController@show')->name('import');
            Route::post('import', 'SubscribersImportController@store')->name('import.store');
        });

        Route::resource('subscribers', "Subscribers\SubscribersController");

        // Messages.
        Route::name('messages.')->namespace('Messages')->prefix('messages')->group(static function () {
            Route::get('/', 'MessagesController@index')->name('index');
            Route::get('draft', 'MessagesController@draft')->name('draft');
            Route::get('{id}/show', 'MessagesController@show')->name('show');
            Route::post('send', 'MessagesController@send')->name('send');
            Route::delete('{id}/delete', 'MessagesController@delete')->name('delete');
            Route::post('send-selected', 'MessagesController@sendSelected')->name('send-selected');
        });

        // Email Services.
        Route::name('email_services.')->namespace('EmailServices')->prefix('email-services')->group(static function () {
            Route::get('/', 'EmailServicesController@index')->name('index');
            Route::get('create', 'EmailServicesController@create')->name('create');
            Route::get('type/{id}', 'EmailServicesController@emailServicesTypeAjax')->name('ajax');
            Route::post('/', 'EmailServicesController@store')->name('store');
            Route::get('{id}/edit', 'EmailServicesController@edit')->name('edit');
            Route::put('{id}', 'EmailServicesController@update')->name('update');
            Route::delete('{id}', 'EmailServicesController@delete')->name('delete');

            Route::get('{id}/test', 'TestEmailServiceController@create')->name('test.create');
            Route::post('{id}/test', 'TestEmailServiceController@store')->name('test.store');
        });
    });

// public routes
// Subscriptions
Route::name('subscriptions.')->prefix('subscriptions')->group(static function () {
    Route::get('unsubscribe/{messageHash}', 'SubscriptionsController@unsubscribe')->name('unsubscribe');
    Route::get('subscribe/{messageHash}', 'SubscriptionsController@subscribe')->name('subscribe');
    Route::put('subscriptions/{messageHash}', 'SubscriptionsController@update')->name('update');
});

// Webview.
Route::name('webview.')->prefix('webview')->group(static function () {
    Route::get('{messageHash}', 'WebviewController@show')->name('show');
});
