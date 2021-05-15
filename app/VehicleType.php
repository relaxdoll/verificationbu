<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $fillable = [
        'name',
        'no_of_wheels',
        'is_head',
        'is_tail',
        'is_independent',
    ];

    public function vehicle_inspection_list()
    {
        return $this->hasMany('App\VehicleInspectionList');
    }
}
