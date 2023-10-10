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
        Schema::create('invitations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedInteger('workspace_id')->index();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('role')->nullable();
            $table->string('email');
            $table->string('token', 40)->unique();
            $table->timestamps();

            $table->foreign('workspace_id')->references('id')->on('workspaces');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
