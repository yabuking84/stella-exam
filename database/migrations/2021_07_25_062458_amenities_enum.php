<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Migration\Data;
use App\Models\AmenitiesEnum as AmenitiesEnumModel;

class AmenitiesEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('amenities_enums', function (Blueprint $table) {
            $table->id();
            $table->string('value')->length(100);
            $table->timestamps();
        });        


        Data::addData(
            ['WiFi', 'Pool', 'Garden', 'Tennis table', 'Parking'],
            "value",
            new AmenitiesEnumModel
        );        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('amenities_enums');
    }


 
}
