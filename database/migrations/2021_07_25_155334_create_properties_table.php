<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('building_id')->unsigned()->index();
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->text('title');
            $table->bigInteger('property_type_id')->unsigned()->index();
            $table->foreign('property_type_id')->references('id')->on('property_type_enums');
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
        Schema::dropIfExists('properties');
    }
}
