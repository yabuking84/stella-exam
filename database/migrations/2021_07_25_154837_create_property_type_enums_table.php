<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Migration\Data;
use App\Models\PropertyTypeEnum as PropertyTypeEnumModel;

class CreatePropertyTypeEnumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_type_enums', function (Blueprint $table) {
            $table->id();
            $table->string('value')->length(100);
            $table->timestamps();
        });

        Data::addData(
            ['1bdr', '2bdr', '3bdr'],
            "value",
            new PropertyTypeEnumModel
        );               
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('property_type_enum');
    }
}


