<?php

namespace App\Http\Controllers\seeders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Building as BuildingModel;
use App\Models\Property as PropertyModel;
use App\Models\PropertyTypeEnum as PropertyTypeEnumModel;
use App\Models\AmenitiesEnum as AmenitiesEnumModel;

class Property extends Controller
{
    //

    public function seedProperty(){

        $building1 = BuildingModel::find(1);
        $building2 = BuildingModel::find(2);

        $bdr1 = PropertyTypeEnumModel::where('value','1bdr')->get()->first();
        $bdr2 = PropertyTypeEnumModel::where('value','2bdr')->get()->first();
        $bdr3 = PropertyTypeEnumModel::where('value','3bdr')->get()->first();

        $amenities1 = AmenitiesEnumModel::whereIn('value',['WiFi','Parking'])->get();
        $amenities2 = AmenitiesEnumModel::whereIn('value',['WiFi','Tennis table'])->get();
        $amenities3 = AmenitiesEnumModel::whereIn('value',['Garden'])->get();
        $amenities4 = AmenitiesEnumModel::whereIn('value',['Garden','Pool'])->get();


        $properties = [];
        $properties[] = [
            'building' => $building1, 
            'title' => 'Unit 1', 
            'property_type' => $bdr1, 
            'amenities' => $amenities1 
        ];
        $properties[] = [
            'building' => $building1, 
            'title' => 'Unit 2', 
            'property_type' => $bdr2, 
            'amenities' => $amenities2 
        ];
        $properties[] = [
            'building' => $building1, 
            'title' => 'Unit 3', 
            'property_type' => $bdr3, 
            'amenities' => $amenities3 
        ];
        $properties[] = [
            'building' => $building2, 
            'title' => 'Unit 4', 
            'property_type' => $bdr1, 
            'amenities' => $amenities4 
        ];


        foreach($properties as $prop){
            $newProp = new PropertyModel();
            $newProp->building()->associate($prop['building']);
            $newProp->title = $prop['title'];
            $newProp->propertyType()->associate($prop['property_type']);
            $newProp->save();


            foreach($prop['amenities'] as $amenity) {
                $newProp->amenities()->attach($amenity->id);
            }

        }

    }


    public function seedAmenities(){ 

        $properties = PropertyModel::all();

        $amenities = [];
        $amenities[] = AmenitiesEnumModel::whereIn('value',['WiFi','Parking'])->get();
        $amenities[] = AmenitiesEnumModel::whereIn('value',['WiFi','Tennis table'])->get();
        $amenities[] = AmenitiesEnumModel::whereIn('value',['Garden'])->get();
        $amenities[] = AmenitiesEnumModel::whereIn('value',['Garden','Pool'])->get();

        $x=0;
        foreach($properties as $prop){
            foreach($amenities[$x] as $amenity) {
                $prop->amenities()->attach($amenity->id);
            }
            $x++;
        }        
    }

}



// insert into test.property (building_id, title, property_type, amenities) VALUES
// insert into test.property (building_id, title, property_type, amenities) VALUES
// insert into test.property (building_id, title, property_type, amenities) VALUES
// insert into test.property (building_id, title, property_type, amenities) VALUES


// (1, 'Unit 1', '1bdr', '{WiFi,Parking}');
// (1, 'Unit 2', '2bdr', '{WiFi,Tennis table}');
// (1, 'Unit 3', '3bdr', '{Garden}');
// (2, 'Unit 4', '1bdr', '{Garden,Pool}');




