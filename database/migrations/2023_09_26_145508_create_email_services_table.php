<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EmailServiceType;

return new class extends Migration
{

    protected function seedEmailServiceTypes()
    {
        $serviceTypes = [
            [
                'id' => EmailServiceType::SES,
                'name' => 'SES'
            ],
            [
                'id' => EmailServiceType::SENDGRID,
                'name' => 'SendGrid'
            ],
            [
                'id' => EmailServiceType::MAILGUN,
                'name' => 'Mailgun'
            ],
            [
                'id' => EmailServiceType::POSTMARK,
                'name' => 'Postmark'
            ],
	    [
		'id'=> EmailServiceType::MAILJET,
		'name' => 'Mailjet',
	    ],
	    [
                'id'=> EmailServiceType::SMTP,
                'name' => 'SMTP',
            ],
	    [
                'id'=> EmailServiceType::POSTAL,
                'name' => 'Postal',
            ],
        ];

        foreach ($serviceTypes as $type) {
            DB::table('email_service_types')
                ->insert(
                    $type + [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
        }
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_service_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

	$this->seedEmailServiceTypes();

	 Schema::create('email_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workspace_id')->index();
            $table->string('name')->nullable();
            $table->unsignedInteger('type_id');
            $table->mediumText('settings');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('email_service_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_services');
	Schema::dropIfExists('email_service_types');
    }
};
