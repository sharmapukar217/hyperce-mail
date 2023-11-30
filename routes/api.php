<?php

use App\Http\Middleware\RequireWorkspace;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware([config('config.throttle_middleware'), RequireWorkspace::class])->group(function () {
    Route::name('api.')->prefix('v1')->namespace('Api')->group(static function () {
        Route::apiResource('campaigns', 'CampaignsController');
        Route::post('campaigns/{id}/send', 'CampaignDispatchController@send')->name('campaigns.send');
        Route::apiResource('subscribers', 'SubscribersController');
        Route::apiResource('tags', 'TagsController');

        Route::apiResource('subscribers.tags', 'SubscriberTagsController')->except(['show', 'update', 'destroy']);
        Route::put('subscribers/{subscriber}/tags', 'SubscriberTagsController@update')->name('subscribers.tags.update');
        Route::delete('subscribers/{subscriber}/tags', 'SubscriberTagsController@destroy')->name('subscribers.tags.destroy');

        Route::apiResource('tags.subscribers', 'TagSubscribersController')->except(['show', 'update', 'destroy']);
        Route::put('tags/{tag}/subscribers', 'TagSubscribersController@update')->name('tags.subscribers.update');
        Route::delete('tags/{tag}/subscribers', 'TagSubscribersController@destroy')->name('tags.subscribers.destroy');

        Route::apiResource('templates', 'TemplatesController');
    });
});

Route::prefix('v1/webhooks')->namespace('Api\Webhooks')->group(static function () {
    Route::post('aws', 'SesWebhooksController@handle')->name('aws');
    Route::post('mailgun', 'MailgunWebhooksController@handle')->name('mailgun');
    Route::post('postmark', 'PostmarkWebhooksController@handle')->name('postmark');
    Route::post('sendgrid', 'SendgridWebhooksController@handle')->name('sendgrid');
    Route::post('mailjet', 'MailjetWebhooksController@handle')->name('mailjet');
    Route::post('postal', 'PostalWebhooksController@handle')->name('postal');
});

Route::get('v1/ping', 'Api\PingController@index');
