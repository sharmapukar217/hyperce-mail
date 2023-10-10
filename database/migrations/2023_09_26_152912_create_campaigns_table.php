<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

	$campaign_statuses = 'campaign_statuses';
	$email_services = 'email_services';
	$templates = 'templates';

        Schema::create('campaigns', function (Blueprint $table) use ($campaign_statuses, $templates, $email_services) {
            $table->increments('id');
            $table->unsignedInteger('workspace_id')->index();
            $table->string('name');
            $table->unsignedInteger('status_id')->default(1);
            $table->unsignedInteger('template_id')->nullable();
            $table->unsignedInteger('email_service_id')->nullable();
            $table->string('subject')->nullable();
            $table->longText('content')->nullable();
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->boolean('is_open_tracking')->default(true);
            $table->boolean('is_click_tracking')->default(true);
            $table->mediumInteger('sent_count')->nullable()->default(0);
            $table->mediumInteger('open_count')->nullable()->default(0);
            $table->mediumInteger('click_count')->nullable()->default(0);
            $table->boolean('send_to_all')->default(false);
            $table->boolean('save_as_draft')->default(true);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on($campaign_statuses);
            $table->foreign('template_id')->references('id')->on($templates);
            $table->foreign('email_service_id')->references('id')->on($email_services);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
