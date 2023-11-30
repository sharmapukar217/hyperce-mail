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
        Schema::table('users', function (Blueprint $table) {
            $table->string('locale', 100)->after('remember_token')->default('en');
            // $table->string('api_token', 80)->after('password')->unique()->nullable()->default(null);
            $table->unsignedInteger('current_workspace_id')->nullable()->default(null)->after('api_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('locale');
            // $table->dropColumn('api_tokens');
            $table->dropColumn('current_workspace_id');
        });
    }
};
