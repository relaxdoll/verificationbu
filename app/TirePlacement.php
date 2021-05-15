<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TirePlacement extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'placement',
        'start_tread_depth',
        'end_tread_depth',
        'start_mileage',
        'end_mileage',
        'is_on_vehicle',
        'vehicle_id',
        'reason_id',
        'checker_id',
        'installer_id',
        'tire_id',
        'is_spare',
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }

    public function tire()
    {
        return $this->belongsTo('App\Tire');
    }

    public function tireRequest()
    {
        return $this->hasOne('App\TireChangeRequest', 'tire_placement_id')->where('is_changed', 0);
    }

}
