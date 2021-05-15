<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Onelink extends Model
{
    protected $fillable = [
        'vehicle_id',
        'licenseplate',
        'speed',
        'odo_meter',
        'latitude',
        'longitude',
        'io_name',
        'admin_level1_name',
        'admin_level2_name',
        'admin_level3_name'
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }
}
