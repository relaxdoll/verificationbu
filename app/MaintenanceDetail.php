<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaintenanceDetail extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'start_mileage',
        'end_mileage',
        'technician_id',
        'maintenance_approval_id',
        'is_update',
    ];
}
