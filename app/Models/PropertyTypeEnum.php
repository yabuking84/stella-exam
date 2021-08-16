<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Property as PropertyModel;

class PropertyTypeEnum extends Model
{
    use HasFactory;
    protected $table = 'property_type_enums';

    
    public function properties() {
        return $this->hasMany(PropertyModel::class, 'property_type_id', 'id');
    }  

}
