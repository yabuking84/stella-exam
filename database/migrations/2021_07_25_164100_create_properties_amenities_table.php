<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesAmenitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_amenities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('property_id')->unsigned()->index();
            $table->foreign('property_id')
                  ->references('id')
                  ->on('properties')->onDelete('cascade');

            $table->bigInteger('amenities_enum_id')->unsigned()->index();
            $table->foreign('amenities_enum_id')
                  ->references('id')
                  ->on('amenities_enums')->onDelete('cascade');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties_amenities');
    }
}
