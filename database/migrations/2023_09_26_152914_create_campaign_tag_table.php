<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\UpgradeMigration;

class CreateCampaignTagTable extends UpgradeMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tags = $this->getTableName('tags');
        $campaigns = $this->getTableName('campaigns');

        Schema::create('campaign_tag', function (Blueprint $table) use ($campaigns, $tags) {
            $table->increments('id');
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('campaign_id');
            $table->timestamps();

            $table->foreign('tag_id')->references('id')->on($tags);
            $table->foreign('campaign_id')->references('id')->on($campaigns);
        });
    }
}