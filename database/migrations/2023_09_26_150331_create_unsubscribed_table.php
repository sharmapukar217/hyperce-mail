<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\UnsubscribeEventType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('unsubscribe_event_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

	$types = [
            UnsubscribeEventType::BOUNCE => 'Bounce',
            UnsubscribeEventType::COMPLAINT => 'Complaint',
            UnsubscribeEventType::MANUAL_BY_ADMIN => 'Manual by Admin',
            UnsubscribeEventType::MANUAL_BY_SUBSCRIBER => 'Manual by Subscriber',
        ];

	foreach ($types as $id => $name) {
            DB::table('unsubscribe_event_types')->insert([ 'id' => $id, 'name' => $name ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unsubscribe_event_types');
    }
};
