<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Building as BuildingModel;

class City extends Model
{
    // use HasFactory;
    protected $table = 'cities';

    public function buildings() {
        return $this->hasMany(BuildingModel::class, 'city_id', 'id');
    }   
}
