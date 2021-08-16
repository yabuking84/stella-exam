<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Models\AmenitiesEnum;
use App\Models\Building as BuildingModel;
use App\Models\PropertyTypeEnum as PropertyTypeEnumModel;
use App\Models\Reservation as ReservationModel;
use App\Models\Availability as AvailabilityModel;

class Property extends Model
{
    use HasFactory;
    protected $table = 'properties';

    public function building() {
        return $this->belongsTo(BuildingModel::class, 'building_id', 'id');
    }

    public function propertyType() {
        return $this->belongsTo(PropertyTypeEnumModel::class, 'property_type_id', 'id');
    }

    public function reservations() {
        return $this->hasMany(ReservationModel::class, 'property_id', 'id');
    }    
    
    public function availability() {
        return $this->hasMany(AvailabilityModel::class, 'property_id', 'id');
    }    

    public function amenities()
    {
        return $this->belongsToMany(
            AmenitiesEnum::class,
            'properties_amenities',
            'property_id',
            'amenities_enum_id'
        );
    }

    
    public function format(){
        return [
            'id' => $this->id,
            'title' => $this->title,
            'building_id' => $this->building->id,
            'city' => $this->building->city->value,
            'property_type' => $this->propertyType->value,
            'amenities' => $this->amenities->map->format()->toArray(),
            'reservations' => $this->reservations->map->format()->toArray(),
            'availability' => $this->availability->map->format()->toArray(),
        ];
    }


    public function scopeMatchByCity($queryRetVal, $city)
    {
        return $queryRetVal->whereHas('building.city', 
        function ($query) use ($city) {
            $query->where('value','=', $city);
        });
    }

    public function scopeMatchByAmenities($queryRetVal, $amenities)
    {
        return $queryRetVal->whereHas('amenities', 
        function ($query) use ($amenities) {
            $query->whereIn('value',$amenities);
        });
    }

    public function scopeMatchByPropertyType($queryRetVal, $propertyType)
    {
        return $queryRetVal->whereHas('propertyType', 
        function ($query) use ($propertyType) {
            $query->whereIn('value',$propertyType);
        });
    }

    public function scopeMatchByDaterange($queryRetVal, $daterange)
    {
        return $queryRetVal
        // Doesnt Have reservations
        ->whereDoesntHave('reservations', 
        function ($query) use ($daterange) {
            $start = $daterange[0];
            $end = $daterange[1];

            // this checks if start or end is between check_in check_out
            $query
            ->where('check_in',"<=",$start)
            ->where('check_out',">=",$start)
            ->orWhere(function($query1) use ($end) {
                $query1
                ->where('check_in',"<=",$end)
                ->where('check_out',">=",$end);
            })

            // this checks if check_in and check_out is inside the date range given 
            ->orWhere(function($query2) use ($daterange) {
                $query2
                ->whereBetween('check_in',$daterange)
                ->orWhereBetween('check_out',$daterange);
            });
        })

        // If available, means daterange doesnt overlaps start_date and end_date and is_blocked is false
        ->whereDoesntHave('availability', 
        function ($query) use ($daterange) {
            // this checks if start or end is between start_date end_date
            $query
            ->where('is_blocked','=',1)
            ->where(function($query3) use ($daterange) {
                $start = $daterange[0];
                $end = $daterange[1];                
                $query3
                ->where('start_date',"<=",$start)
                ->where('end_date',">=",$start)
                ->orWhere(function($query1) use ($end) {
                    $query1
                    ->where('start_date',"<=",$end)
                    ->where('end_date',">=",$end);
                })
    
                // this checks if start_date and end_date is inside the date range given 
                ->orWhere(function($query2) use ($daterange) {
                    $query2
                    ->whereBetween('start_date',$daterange)
                    ->orWhereBetween('end_date',$daterange);
                });
            });
        })

        ; // end eloquent 
    }

    public function scopeMatchByFlexibility($queryRetVal, $data) {
        $type = $data['type'];
        $months = $data['months'];
        $city = $data['city'];

        // convert 3 letter abbreviation on months to number 
        $months_array = [];
        foreach($months as $month) {
            $date = Carbon::now();
            $date->set('month',date("n",strtotime($month)));
            $months_array[] = $date->month;         
        }


        if($type=="month") {

            return $queryRetVal

            // Doesnt Have reservations
            ->whereDoesntHave('reservations', 
            function ($query) use ($months_array) {

                // use this if month is ex. Jun AND Mar
                // $query
                // ->whereIn(DB::raw('month(check_in)'),$months_array)
                // ->orWhereIn(DB::raw('month(check_out)'),$months_array);

                // use this if month is ex. Jun OR Mar
                foreach($months_array as $ma){
                    $query->where(function ($query2) use ($ma) {
                        $query2
                        ->whereMonth('check_in',$ma)
                        ->orWhereMonth('check_out',$ma);
                    });
                }
            })

            // If available
            ->whereDoesntHave('availability', 
            function ($query) use ($months_array) {

                $query
                ->where('is_blocked','=',1)
                ->where(function($query2) use ($months_array) {

                    // use this if month is ex. Jun AND Mar
                    // $query2
                    // ->whereIn(DB::raw('month(start_date)'),$months_array)
                    // ->orWhereIn(DB::raw('month(end_date)'),$months_array);
                    
                    // use this if month is ex. Jun OR Mar
                    foreach($months_array as $ma){
                        $query2->where(function ($query3) use ($ma) {
                            $query3
                            ->whereMonth('start_date',$ma)
                            ->orWhereMonth('end_date',$ma);
                        });
                    }
                });

            })

            ; // end eloquent 

        } 

        else if($type=="weekend") {
            $weekends = [];         
            foreach($months_array as $ma) {
                $date = Carbon::now();
                $date->set('month',$ma);


                // check if dubai or others for weekend
                if($city=="Dubai") {
                    $weekend_day1 = "Friday";
                    $weekend_day2 = "Saturday";
                } else {
                    $weekend_day1 = "Saturday";
                    $weekend_day2 = "Sunday"; 
                }

                // echo "<br><br><br> xxx ".$date->englishMonth." = ".$date->daysInMonth." days xxxx <br>";

                $start_end_dates = [];
                for($x = 1; $x<=$date->daysInMonth; $x++) {
                    $date->set('day',$x);

                    $start_date = "";
                    $end_date = "";

                    // echo "Day: ".$x." <br>";

                    // checks if weekend
                    if($date->is($weekend_day1) || $date->is($weekend_day2)) {
                        
                        
                        // echo "Today is ".$date->englishDayOfWeek." ".$date->get('day')." ".$date->format('Y-m-d')."<br>";
                        $today = $date->englishDayOfWeek;
                        $date->addDay();

                        // echo "Tomorrow is ".$date->englishDayOfWeek." ".$date->get('day')." ".$date->format('Y-m-d')."<br>";
                        $tomorrow = $date->englishDayOfWeek;
                        $date->subDay();

                        // echo "Today is ".$date->englishDayOfWeek." ".$date->get('day')." ".$date->format('Y-m-d')."<br>";

                        // add if today and tomorrow are weekend
                        if($today == $weekend_day1 && $tomorrow == $weekend_day2) {
                            $start_date = $date->format('Y-m-d');
                            $date->addDay();
                            $end_date = $date->format('Y-m-d');
                            $date->subDay();

                            // echo "add dates <br>";
                            $start_end_dates[] = [
                                "start_date" => $start_date,
                                "end_date"   => $end_date,
                            ];                             
                        }
                    }
                }
                $weekends[] = [
                    "month"=>$date->get('month'), 
                    "dates" => $start_end_dates 
                    
                ];
            }


            $retVal = null;
            foreach($weekends as $weekend) {
                foreach($weekend['dates'] as $date) {
                    if($retVal) {
                        // grouping them so that it will isolate each weekend date
                        $retVal = $retVal->orWhere(function($query4) use ($date) {
                            $query4->matchByDaterange([$date['start_date'],$date['end_date']]);
                        });
                    }
                    else {
                        $retVal = $queryRetVal->matchByDaterange([$date['start_date'],$date['end_date']]);
                    }
                }
            }
            return $retVal;

        }

    }
}
