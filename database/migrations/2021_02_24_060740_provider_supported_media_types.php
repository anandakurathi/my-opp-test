<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProviderSupportedMediaTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_supported_media_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('media_type_id')->unsigned();
            $table->bigInteger('provider_id')->unsigned();
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
        Schema::dropIfExists('provider_supported_media_types');
    }
}
