<?php

namespace App\Http\Controllers\seeders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Building as BuildingModel;
use App\Models\City as CityModel;

class Building extends Controller
{
    //

    public function seedBuilding(){

        $cities = CityModel::get();
        
        foreach($cities as $city) {
            $building = new BuildingModel();
            $building->city()->associate($city);
            $building->save();
        }

    }    
}


// insert into test.building (city) values 
// insert into test.building (city) values 

// ('Dubai');
// ('Montreal');