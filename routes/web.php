<?php

use App\Http\Controllers\Auth\ApiTokenController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Campaigns\CampaignsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailServices\EmailServicesController;
use App\Http\Controllers\EmailServices\TestEmailServiceController;
use App\Http\Controllers\Messages\MessagesController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\Subscribers\SubscribersController;
use App\Http\Controllers\Subscribers\SubscribersImportController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\Tags\TagsController;
use App\Http\Controllers\Templates\TemplatesController;
use App\Http\Controllers\WebviewController;
use App\Http\Controllers\Workspaces\PendingInvitationController;
use App\Http\Controllers\Workspaces\SwitchWorkspaceController;
use App\Http\Controllers\Workspaces\WorkspaceInvitationsController;
use App\Http\Controllers\Workspaces\WorkspacesController;
use App\Http\Controllers\Workspaces\WorkspaceUsersController;
use App\Http\Middleware\OwnsCurrentWorkspace;
use App\Http\Middleware\RequireWorkspace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'verify' => true,
    'reset' => config('config.auth.password_reset'),
    // 'verify' => config('config.auth.register', false),
    'register' => config('config.auth.register', false),
]);

Route::get('setup', SetupController::class)->name('setup');

// Auth.
Route::middleware('auth')->namespace('Auth')->group(static function () {
    // Logout.
    Route::get('logout', [LoginController::class, "logout"]);

    // Profile
    Route::middleware('verified')
        ->name('profile.')
        ->prefix('profile')
        ->group(static function () {
            Route::get("/", [ProfileController::class, "show"])->name("show");
            Route::put("/", [ProfileController::class, "update"])->name("update");
            Route::get("/edit", [ProfileController::class, "edit"])->name("edit");

            // Password
            Route::prefix("password")->name("password.")->group(static function () {
                Route::put("/", [ChangePasswordController::class, "update"])->name("update");
                Route::get("/edit", [ChangePasswordController::class, "edit"])->name("edit");
            });
        });

    // Api Tokens
    Route::middleware('verified')->name('api-tokens.')->prefix('api-tokens')->group(static function () {
        Route::get('/', [ApiTokenController::class, 'index'])->name('index');
        Route::post('/', [ApiTokenController::class, 'store'])->name('store');
        Route::delete('{tokenid}', [ApiTokenController::class, 'destroy'])->name('destroy');
    });
});

// Workspace User Management.
Route::middleware(['auth', 'verified', RequireWorkspace::class, OwnsCurrentWorkspace::class])
    ->name('users.')
    ->prefix('users')
    ->group(static function () {
        Route::get('/', [WorkspaceUsersController::class, 'index'])->name('index');
        Route::delete('{userId}', [WorkspaceUsersController::class, 'destroy'])->name('destroy');

        // Invitations.
        Route::name('invitations.')->prefix('invitations')
            ->group(static function () {
            Route::post('/', [WorkspaceInvitationsController::class, 'store'])->name('store');
            Route::delete('{invitation}', [WorkspaceInvitationsController::class, 'destroy'])
                ->name('destroy');
        });
    });

// Workspace Management.
Route::middleware(['auth', 'verified', RequireWorkspace::class])
    ->group(static function () {

        Route::resource('workspaces', WorkspacesController::class)->except(['create', 'show', 'destroy']);

        // Workspace Switching.
        Route::get('workspaces/{workspace}/switch', [SwitchWorkspaceController::class, 'switch'])
            ->name('workspaces.switch');

        // Invitations.
        Route::post('workspaces/invitations/{invitation}/accept', [PendingInvitationController::class, 'accept'])
            ->name('workspaces.invitations.accept');
        Route::post('workspaces/invitations/{invitation}/reject', [PendingInvitationController::class, 'reject'])
            ->name('workspaces.invitations.reject');
    });

Route::middleware(['auth', 'verified', RequireWorkspace::class])->group(static function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    // Campaigns
    Route::resource('campaigns', CampaignsController::class)->except(['show', 'destroy']);
    Route::name('campaigns.')->prefix('campaigns')->namespace('Campaigns')->group(static function () {
        Route::get('sent', [CampaignsController::class, "sent"])->name('sent');
        Route::get('{id}', [CampaignsController::class, "show"])->name('show');
        Route::get('{id}/preview', [CampaignsController::class, "todo"])->name('preview');
        Route::put('{id}/send', [CampaignsController::class, "send"])->name('send');
        Route::get('{id}/status', [CampaignsController::class, "status"])->name('status');
        Route::post('{id}/test', [CampaignsController::class, "handle"])->name('test');
    });

    // Tags
    Route::resource('tags', TagsController::class)->except(['show']);

    // Templates
    Route::resource('templates', TemplatesController::class);

    // Subscribers.
    Route::resource('subscribers', SubscribersController::class);
    Route::name('subscribers.')->prefix('subscribers')->namespace('Subscribers')->group(static function () {
        Route::get('export', [SubscribersController::class, "export"])->name('export');
        Route::get('import', [SubscribersImportController::class, "show"])->name('import');
        Route::post('import', [SubscribersImportController::class, "store"])->name('import.store');
    });

    // Messages.
    Route::name('messages.')->prefix('messages')->group(static function () {
        Route::get('/', [MessagesController::class, "index"])->name('index');
        Route::get('draft', [MessagesController::class, "draft"])->name('draft');
        Route::get('{id}/show', [MessagesController::class, "show"])->name('show');
        Route::post('send', [MessagesController::class, "send"])->name('send');
        Route::delete('{id}/delete', [MessagesController::class, "delete"])->name('delete');
        Route::post('send-selected', [MessagesController::class, "sendSelected"])->name('send-selected');
    });

    // Email Services.
    Route::name('email_services.')->prefix('email-services')->group(static function () {
        Route::get('/', [EmailServicesController::class, "index"])->name('index');
        Route::get('create', [EmailServicesController::class, "create"])->name('create');
        Route::get('type/{id}', [EmailServicesController::class, "emailServicesTypeAjax"])->name('ajax');
        Route::post('/', [EmailServicesController::class, "store"])->name('store');
        Route::get('{id}/edit', [EmailServicesController::class, "edit"])->name('edit');
        Route::put('{id}', [EmailServicesController::class, "update"])->name('update');
        Route::delete('{id}', [EmailServicesController::class, "delete"])->name('delete');

        Route::get('{id}/test', [TestEmailServiceController::class, "create"])->name('test.create');
        Route::post('{id}/test', [TestEmailServiceController::class, "store"])->name('test.store');
    });

});



// public routes

// Subscriptions
Route::name('subscriptions.')->prefix('subscriptions')->group(static function () {
    Route::get('unsubscribe/{messageHash}', [SubscriptionsController::class,'unsubscribe'])->name('unsubscribe');
    Route::get('subscribe/{messageHash}',[SubscriptionsController::class, 'subscribe'])->name('subscribe');
    Route::put('subscriptions/{messageHash}',[SubscriptionsController::class, 'update'])->name('update');
});

// Webview.
Route::name('webview.')->prefix('webview')->group(static function () {
    Route::get('{messageHash}', WebviewController::class)->name('show');
});