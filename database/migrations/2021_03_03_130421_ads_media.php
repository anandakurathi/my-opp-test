<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdsMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_media', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('media_type_id')->unsigned();
            $table->bigInteger('provider_id')->unsigned();
            $table->string('ad_name');
            $table->string('file_path');
            $table->string('preview_path');
            $table->enum('status', ['A', 'B']); // A=> Active, B=> Block
            $table->timestamps();
            $table->foreign('media_type_id')->references('id')->on('media_types');
            $table->foreign('provider_id')->references('id')->on('providers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_media');
    }
}
