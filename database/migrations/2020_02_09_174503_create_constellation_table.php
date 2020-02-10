<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstellationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constellation', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('constellation_name');
            $table->integer('overall_level');
            $table->string('overall_content');
            $table->integer('love_level');
            $table->string('love_content');
            $table->integer('business_level');
            $table->string('business_content');
            $table->integer('fortune_level');
            $table->string('fortune_content');
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
        Schema::dropIfExists('constellation');
    }
}
