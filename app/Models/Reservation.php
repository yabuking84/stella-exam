<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Property as PropertyModel;

class Reservation extends Model
{
    use HasFactory;
    protected $table = 'reservations';


    public function property() {
        return $this->belongsTo(PropertyModel::class, 'property_id', 'id');
    }

    
    public function format(){
        return [
            // 'id' => $this->id,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
        ];
    }        
    
}
