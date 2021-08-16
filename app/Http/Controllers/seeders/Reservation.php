<?php

namespace App\Http\Controllers\seeders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Property as PropertyModel;
use App\Models\Reservation as ReservationsModel;

class Reservation extends Controller
{
    //
    public function seedReservation(){
        
        $prop1 = PropertyModel::find(1);
        $prop2 = PropertyModel::find(2);

        $reservations = [];
        $reservations[] = [
            "check_in"  => '2021-05-01',
            "check_out" => '2021-05-10',
            "property"  => $prop1,
        ];
        $reservations[] = [
            "check_in"  => '2021-06-01',
            "check_out" => '2021-06-03',
            "property"  => $prop1,
        ];
        $reservations[] = [
            "check_in"  => '2021-06-02',
            "check_out" => '2021-06-07',
            "property"  => $prop2,
        ];

        foreach($reservations as $rsv){
            $newReserv = new ReservationsModel();
            $newReserv->check_in = $rsv['check_in'];
            $newReserv->check_out = $rsv['check_out'];
            $newReserv->property()->associate($rsv['property']);
            $newReserv->save();
        }
        

    }

}
