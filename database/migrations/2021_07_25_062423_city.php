<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Migration\Data;
use App\Models\City as CityModel;

class City extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('value')->length(100);
            $table->timestamps();
        });        

        Data::addData(
            ['Dubai', 'Montreal'],
            "value",
            new CityModel
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('cities');
    }



}
