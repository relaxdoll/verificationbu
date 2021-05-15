<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TireChangeRequest extends Model
{
    protected $fillable = [
        'tire_placement_id',
        'reason_id',
        'driver_id',
        'technician_id',
        'is_changed',
        'is_rejected',
        'link',
    ];
}
