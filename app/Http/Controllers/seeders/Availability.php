<?php

namespace App\Http\Controllers\seeders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Property as PropertyModel;
use App\Models\Availability as AvailabilityModel;

class Availability extends Controller
{
    //
    public function seedAvailability(){
        
        $prop1 = PropertyModel::find(1);

        $avails = [];
        $avails[] = [
            "property"  => $prop1,
            "start_date"  => '2021-07-01',
            "end_date" => '2021-07-20',
            "is_blocked" => true,
        ];

        foreach($avails as $avl){

            $newAvl = new AvailabilityModel();
            $newAvl->property()->associate($avl['property']);
            $newAvl->start_date = $avl['start_date'];
            $newAvl->end_date = $avl['end_date'];
            $newAvl->is_blocked = $avl['is_blocked'];
            $newAvl->save();

        }

        

    }    
}
