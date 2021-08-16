<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\PropertyRepository;
use Carbon\Carbon;

use App\Models\Property as PropertyModel;

class AvailableUnits extends Controller
{
    //
	public function __construct(PropertyRepository $property) {
		$this->property = $property;
	}

    public function getAvailableUnits(Request $rqst) {


        $city = $rqst->city;
        $propertyType = $rqst->apartmentType;
        $amenities = $rqst->amenities;

        $date = $rqst->date;

        $flexible = $rqst->flexible;

        $match = [];
        $alternative = [];
        $other = [];

        if($city) {
            $search_result = $this->property->startSearch($city,$amenities,$propertyType);
        }

        if($city && $date) {   
                        
            $match = $this->property->searchByDateAvailability($search_result,[$date['start'],$date['end']]);

            // alternative
            if(!count($match)) {
                $alternative = $this->property->getAlternativeByDaterange($city,$amenities,$propertyType,[$date['start'],$date['end']]);
            }

            // other
            if(!count($match) && !count($alternative)) {
                $other = $this->property->getOtherByDaterange($city,$amenities,$propertyType,[$date['start'],$date['end']]);
            }
        } 
        else if($city && $flexible) {    
            $match = $this->property->searchByTypeFlexibility($search_result,$city,$flexible);
        } 
        else {
            return null;
        }

        $retVal = [
            'match' => $match, 
            'alternative' => $alternative, 
            'other' => $other
        ];

        // dd($retVal);

        return response()->json($retVal);        
            
        
    }






    
}
