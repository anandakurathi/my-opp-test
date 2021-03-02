<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MediaTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_types', function (Blueprint $table) {
            $table->id();
            $table->char('name', 4)->unique();
            $table->enum('category', ['image', 'video']);
            $table->enum('status', ['A', 'B']); // A=> Active, B=> Block
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_types');
    }
}
