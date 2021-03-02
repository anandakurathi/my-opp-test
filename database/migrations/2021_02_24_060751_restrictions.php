<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Restrictions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restrictions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('provider_supported_media_type_id')->unsigned();
            $table->string('key_name', 45);
            $table->string('key_value', 10);
            $table->enum('status', ['A', 'B']); // A=> Active, B=> Block
            $table->timestamps();
            $table->foreign('provider_supported_media_type_id')
                ->references('id')->on('provider_supported_media_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restrictions');
    }
}
