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
        $unsubscribe_event_types = 'unsubscribe_event_types';

        Schema::create('subscribers', function (Blueprint $table) use ($unsubscribe_event_types) {
            $table->increments('id');
            $table->unsignedInteger('workspace_id')->index();
            $table->uuid('hash')->unique();
            $table->string('email')->index();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->jsonb('meta')->nullable();
            $table->timestamp('unsubscribed_at')->nullable()->index();
            $table->unsignedInteger('unsubscribe_event_id')->nullable();
            $table->timestamps();

            $table->index('created_at');
            $table->foreign('unsubscribe_event_id')->references('id')->on($unsubscribe_event_types);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
