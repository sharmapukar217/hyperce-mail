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
     	 Schema::create('workspaces', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('owner_id')->index();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('workspace_users', function (Blueprint $table) {
            $table->unsignedInteger('workspace_id');
            $table->unsignedInteger('user_id')->index();
            $table->string('role', 20);
            $table->timestamps();

            $table->foreign('workspace_id')->references('id')->on('workspaces');
            $table->unique(['workspace_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspaces');
	Schema:dropIfExists('workspace_users');
    }
};
