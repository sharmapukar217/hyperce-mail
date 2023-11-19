<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PlanType;

return new class extends Migration
{

    protected function seedPlanTypes()
    {
        $planTypes = [
            [
                'id' => PlanType::FREE,
                'plan' => 'Free'
            ],
            [
                'id' => PlanType::BASIC,
                'plan' => 'Basic'
            ],
            [
                'id' => PlanType::PRO,
                'plan' => 'Pro'
            ]
        ];

        foreach ($planTypes as $type) {
            DB::table('plan_types')->insert($type);
        }
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan');
        });

	    $this->seedPlanTypes();

	    Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workspace_id')->index();
            $table->unsignedInteger('plan_id');
            $table->dateTime("enrolled_at");
            $table->dateTime("expires_at");

            $table->foreign('plan_id')->references('id')->on('plan_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
	    Schema::dropIfExists('plan_types');
    }
};