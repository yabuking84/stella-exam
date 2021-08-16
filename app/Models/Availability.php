<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Property as PropertyModel;

class Availability extends Model
{
    use HasFactory;
    protected $table = 'availability';


    public function property() {
        return $this->belongsTo(PropertyModel::class, 'property_id', 'id');
    }

    
    public function format(){
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_blocked' => $this->is_blocked,
        ];
    }    
}
