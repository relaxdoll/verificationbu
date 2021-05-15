<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaintenanceInventoryDetail extends Model
{
    protected $fillable = [
        'quantity_used',
        'maintenance_detail_id',
        'inventory_id',
    ];

    public function maintenance_detail()
    {
        $this->belongsTo('App\MaintenanceDetail');
    }

    public function inventory()
    {
        return $this->belongsTo('App\Technician');
    }
}
