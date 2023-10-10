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
        Schema::create('campaign_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        DB::table('campaign_statuses')
            ->insert([
               ['name' => 'Draft'],
               ['name' => 'Queued'],
               ['name' => 'Sending'],
               ['name' => 'Sent'],
	       ['name' => 'Cancelled'],
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_statuses');
    }
};
