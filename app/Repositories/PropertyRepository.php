<?php

namespace App\Repositories;

use App\Models\Property as PropertyModel;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PropertyRepository
{

	protected $property;

	public function __construct(PropertyModel $property) {
	    $this->property = $property;
	}



	public function startSearch($city,$amenities,$propertyType) {
        // $retVal = $this->property->matchByCity($city);
        $retVal = new PropertyModel();
        $retVal = $retVal->matchByCity($city);
    
        if($amenities)
        $retVal = $retVal->matchByAmenities($amenities);
        
        if($propertyType)
        $retVal = $retVal->matchByPropertyType($propertyType);

        return $retVal;

    }

	public function searchByDateAvailability($result,$daterange) {

        if($daterange)
        return $result->matchByDaterange($daterange)
        ->get()->pluck('id')->toArray();
        // ->get()->map->format()->toArray();
        else 
        return null;
	}




	public function searchByTypeFlexibility($result,$city,$type_months) {

        if($type_months)
		return $result->matchByFlexibility([
            "city" => $city,
            "months" => $type_months['months'],
            "type" => $type_months['type'],
        ])
        ->get()->pluck('id')->toArray();
        // ->get()->map->format()->toArray();
        else 
        return null;
	}





























    public function getAlternativeByDaterange($city,$amenities,$propertyType,$daterange){

        $retVal = [];

        $start = $daterange[0];
        $end = $daterange[1];
        $start_carbon = Carbon::parse($start);
        $end_carbon = Carbon::parse($end);

        // echo "<br>";
        // echo "1 Start ".$start_carbon->format('Y-m-d');
        // echo "<br>";
        // echo "1 End ".$end_carbon->format('Y-m-d');
        // echo "<br>";

        // this will only check for alternatives max 3 times
        $search_result = null;
        for($x=0; $x<=3; $x++) {


                // not sure but need to re run the search_result because it will have a zero result.
                $search_result = $this->startSearch($city,$amenities,$propertyType);

                $start_date = $start_carbon->format('Y-m-d');
                $end_date = $end_carbon->format('Y-m-d');

                $start_carbon = Carbon::parse($start_date);
                $end_carbon = Carbon::parse($end_date);
                
                $diff = $end_carbon->diffInDays($start_carbon);

                $start_carbon->addDays($diff + 1);
                $end_carbon->addDays($diff + 1);

                $new_start_date = $start_carbon->format('Y-m-d');
                $new_end_date = $end_carbon->format('Y-m-d');

                // echo "<br>";
                // echo "Start ".$new_start_date;
                // echo "<br>";
                // echo "End ".$new_end_date;
                // echo "<br>";

                // echo "search_result start: ".$search_result->count();
                // echo "<br>"; 

                $search_result = $search_result->matchByDaterange([$new_start_date,$new_end_date]);

                foreach($search_result->get() as $sr) {
                    $retVal[] = [
                        "id"=> $sr->id,
                        "availableStarting"=>$new_start_date,
                        "availableEnding"=>$new_end_date,
                    ];
                }

                // echo "search_result after daterange: ".$search_result->count();
                // echo "<br>";

        }

        return $retVal;
    }



    public function getOtherByDaterange($city,$amenities,$propertyType,$daterange){
        $retVal = [];
        
        // not sure but need to re run the search_result because it will have a zero result.
        $property = new PropertyModel();
        $search_result = $property->matchByCity($city)->matchByDaterange($daterange)
        ->get()->map->format()->toArray();
        
        $retVal = $search_result;
        
        return $retVal;

    }


}