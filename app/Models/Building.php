<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\City as CityModel;
use App\Models\Property as PropertyModel;

class Building extends Model
{
    use HasFactory;
    protected $table = 'buildings';

    public function city() {
        return $this->belongsTo(CityModel::class, 'city_id', 'id');
    }

    public function properties() {
        return $this->hasMany(PropertyModel::class, 'building_id', 'id');
    }       

  
}
