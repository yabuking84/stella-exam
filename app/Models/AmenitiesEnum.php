<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmenitiesEnum extends Model
{
    use HasFactory;
    protected $table = 'amenities_enums';

    
    public function format(){
        // return [
        //     'id' => $this->id,
        //     'value' => $this->value,
        // ];

        return $this->value;
    }  

}
